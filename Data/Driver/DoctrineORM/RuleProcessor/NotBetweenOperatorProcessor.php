<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor;

use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class NotBetweenOperatorProcessor extends AbstractRuleProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process(QueryBuilder $qb, FilterRule $rule, $column)
    {
        $qb->andWhere($qb->expr()->orX($qb->expr()->lt($column, $this->getQueryParameter($rule) . 'Start'), $qb->expr()->gt($column, $this->getQueryParameter($rule) . 'End')));
        $qb->setParameter($this->getQueryParameterName($rule) . 'Start', $rule->getValue()['start']);
        $qb->setParameter($this->getQueryParameterName($rule) . 'End', $rule->getValue()['end']);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FilterRule $rule, $column)
    {
        return $rule->getOperator() === FilterOperatorMap::OPERATOR_NOT_BETWEEN;
    }
}
