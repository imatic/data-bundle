<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\RuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ContainsOperatorProcessor extends AbstractRuleProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process($qb, FilterRule $rule, $column)
    {
        $qb->andWhere($qb->expr()->{$rule->getOperator()}($column, $this->getQueryParameter($rule)));
        $qb->setParameter($this->getQueryParameterName($rule), '%' . $rule->getValue() . '%');
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FilterRule $rule, $column)
    {
        return in_array($rule->getOperator(), [
            FilterOperatorMap::OPERATOR_CONTAINS,
            FilterOperatorMap::OPERATOR_NOT_CONTAINS
        ]);
    }
}
