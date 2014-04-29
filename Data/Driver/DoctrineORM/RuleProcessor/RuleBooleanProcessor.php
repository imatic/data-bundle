<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule\FilterRuleBoolean;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Doctrine\ORM\QueryBuilder;

class RuleBooleanProcessor extends AbstractRuleProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process(QueryBuilder $qb, FilterRule $rule, $column)
    {
        if (FilterOperatorMap::OPERATOR_EMPTY === $rule->getOperator()) {
            $qb->andWhere($qb->expr()->isNull($column));
        } else {
            switch ($rule->getValue()) {
                case FilterRuleBoolean::NO:
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
    public function supports(QueryBuilder $qb, FilterRule $rule, $column)
    {
        return $rule instanceof FilterRuleBoolean;
    }

}
