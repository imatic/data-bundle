<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\BooleanRule;

class RuleBooleanProcessor extends AbstractFilterRuleProcessor
{
    public function process($qb, FilterRule $rule, $column)
    {
        if (FilterOperatorMap::OPERATOR_EMPTY === $rule->getOperator()) {
            $qb->andWhere($qb->expr()->isNull($column));
        } else {
            switch ($rule->getValue()) {
                case BooleanRule::NO:
                case false:
                    $qb->andWhere($qb->expr()->eq($column, 'false'));
                    break;
                default:
                    $qb->andWhere($qb->expr()->eq($column, 'true'));
            }
        }
    }

    public function supports($qb, FilterRule $rule, $column)
    {
        return parent::supports($qb, $rule, $column) && $rule instanceof BooleanRule;
    }
}
