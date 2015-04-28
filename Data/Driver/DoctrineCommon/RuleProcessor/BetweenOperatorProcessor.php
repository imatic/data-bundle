<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\RuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class BetweenOperatorProcessor extends AbstractRuleProcessor
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
            $conditions[] = $qb->expr()->gte($column, $this->getQueryParameter($rule) . 'Start');
            $qb->setParameter($this->getQueryParameterName($rule) . 'Start', $rule->getValue()['start'], $rule->getType());
        }

        if ($end) {
            $conditions[] = $qb->expr()->lte($column, $this->getQueryParameter($rule) . 'End');
            $qb->setParameter($this->getQueryParameterName($rule) . 'End', $rule->getValue()['end'], $rule->getType());
        }

        $qb->andWhere(call_user_func_array([$qb->expr(), 'andX'], $conditions));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FilterRule $rule, $column)
    {
        return  $rule->getOperator() === FilterOperatorMap::OPERATOR_BETWEEN;
    }
}
