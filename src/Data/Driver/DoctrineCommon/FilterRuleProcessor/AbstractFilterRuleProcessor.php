<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

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

        $qb->andWhere(\call_user_func_array([$qb->expr(), 'orX'], $exprs));
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
}
