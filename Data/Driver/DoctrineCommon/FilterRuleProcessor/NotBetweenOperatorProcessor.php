<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class NotBetweenOperatorProcessor extends AbstractFilterRuleProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process($qb, FilterRule $rule, $column)
    {
        $start = $rule->getValue()['start'];
        $end = $rule->getValue()['end'];

        $conditions = [];
        if ($start) {
            $conditions[] = $qb->expr()->lt($column, $this->getQueryParameter($rule) . 'Start');
            $qb->setParameter($this->getQueryParameterName($rule) . 'Start', $rule->getValue()['start'], $rule->getType());
        }

        if ($end) {
            $conditions[] = $qb->expr()->gt($column, $this->getQueryParameter($rule) . 'End');
            $qb->setParameter($this->getQueryParameterName($rule) . 'End', $rule->getValue()['end'], $rule->getType());
        }

        $qb->andWhere(call_user_func_array([$qb->expr(), 'orX'], $conditions));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($qb, FilterRule $rule, $column)
    {
        return
            parent::supports($qb, $rule, $column)
            && $rule->getOperator() === FilterOperatorMap::OPERATOR_NOT_BETWEEN
        ;
    }
}
