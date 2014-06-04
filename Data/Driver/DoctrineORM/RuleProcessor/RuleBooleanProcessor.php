<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\BooleanRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class RuleBooleanProcessor extends AbstractRuleProcessor
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function supports(FilterRule $rule, $column)
    {
        return $rule instanceof BooleanRule;
    }

}
