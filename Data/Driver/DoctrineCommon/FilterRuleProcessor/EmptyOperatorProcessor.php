<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class EmptyOperatorProcessor extends AbstractFilterRuleProcessor
{
    protected function processOneColumn($qb, FilterRule $rule, $column)
    {
        return $qb->expr()->{$rule->getOperator()}($column);
    }

    public function supports($qb, FilterRule $rule, $column)
    {
        return
            parent::supports($qb, $rule, $column)
            && \in_array($rule->getOperator(), [
                FilterOperatorMap::OPERATOR_EMPTY,
                FilterOperatorMap::OPERATOR_NOT_EMPTY,
            ], true);
    }
}
