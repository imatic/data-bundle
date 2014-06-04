<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor;

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
        $qb->andWhere($qb->expr()->andX($qb->expr()->gte($column, $this->getQueryParameter($rule) . 'Start'), $qb->expr()->lte($column, $this->getQueryParameter($rule) . 'End')));
        $qb->setParameter($this->getQueryParameterName($rule) . 'Start', $rule->getValue()['start']);
        $qb->setParameter($this->getQueryParameterName($rule) . 'End', $rule->getValue()['end']);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FilterRule $rule, $column)
    {
        return  $rule->getOperator() === FilterOperatorMap::OPERATOR_BETWEEN;
    }
}
