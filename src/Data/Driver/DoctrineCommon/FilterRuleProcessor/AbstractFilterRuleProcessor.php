<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRuleProcessorInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
abstract class AbstractFilterRuleProcessor implements FilterRuleProcessorInterface
{
    public function supports(object $qb, FilterRule $rule, $column): bool
    {
        return $qb instanceof ORMQueryBuilder || $qb instanceof DBALQueryBuilder;
    }

    public function process(object $qb, FilterRule $rule, $column): void
    {
        $fixedColumns = \is_array($column) ? $column : [$column];

        $exprs = [];
        foreach ($fixedColumns as $oneColumn) {
            $exprs[] = $this->processOneColumn($qb, $rule, $oneColumn);
        }

        $qb->andWhere(
            $qb instanceof DBALQueryBuilder
                ? $qb->expr()->or(...$exprs)
                : $qb->expr()->orX(...$exprs)
        );
    }

    /**
     * @param ORMQueryBuilder|DBALQueryBuilder $qb
     * @param mixed $column
     *
     * @return mixed
     */
    abstract protected function processOneColumn($qb, FilterRule $rule, $column);

    protected function getQueryParameter(FilterRule $rule): string
    {
        return \sprintf(
            $rule->getOption('query_parameter_format'),
            ':' . $this->getQueryParameterName($rule)
        );
    }

    protected function getQueryParameterName(FilterRule $rule): string
    {
        return $rule->getName();
    }

    /**
     * Resolves the parameter type to a value accepted by the given query builder.
     *
     * ORM's QueryBuilder::setParameter() accepts null and infers the type from the value,
     * DBAL's does not accept null at all, so a default has to be supplied there.
     *
     * @param ORMQueryBuilder|DBALQueryBuilder $qb
     */
    protected function resolveType(
        $qb,
        FilterRule $rule,
        ParameterType|ArrayParameterType|string $dbalDefault = ParameterType::STRING,
    ): ParameterType|ArrayParameterType|string|null {
        return $rule->getType() ?? ($qb instanceof DBALQueryBuilder ? $dbalDefault : null);
    }
}
