<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DefaultRuleProcessor extends AbstractFilterRuleProcessor
{
    protected function processOneColumn($qb, FilterRule $rule, $column)
    {
        $qb->setParameter($this->getQueryParameterName($rule), $rule->getValue(), $rule->getType());

        return $qb->expr()->{$rule->getOperator()}($column, $this->getQueryParameter($rule));
    }
}
