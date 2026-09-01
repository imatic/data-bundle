<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class BetweenOperatorProcessor extends AbstractFilterRuleProcessor
{
    protected function processOneColumn($qb, FilterRule $rule, $column)
    {
        $start = $rule->getValue()['start'];
        $end = $rule->getValue()['end'];

        $conditions = [];
        if ($start) {
            $conditions[] = $qb->expr()->gte($column, $this->getQueryParameter($rule) . 'Start');
            $qb->setParameter($this->getQueryParameterName($rule) . 'Start', $rule->getValue()['start'], $this->resolveType($qb, $rule));
        }

        if ($end) {
            $conditions[] = $qb->expr()->lte($column, $this->getQueryParameter($rule) . 'End');
            $qb->setParameter($this->getQueryParameterName($rule) . 'End', $rule->getValue()['end'], $this->resolveType($qb, $rule));
        }

        return $conditions
            ? (
                $qb instanceof DBALQueryBuilder
                    ? $qb->expr()->and(...$conditions)
                    : $qb->expr()->andX(...$conditions)
            )
            : '1=1';
    }

    public function supports(object $qb, FilterRule $rule, $column): bool
    {
        return
            parent::supports($qb, $rule, $column)
            && $rule->getOperator() === FilterOperatorMap::OPERATOR_BETWEEN;
    }
}
