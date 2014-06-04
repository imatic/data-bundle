<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\RuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CallbackProcessor extends AbstractRuleProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process($qb, FilterRule $rule, $column)
    {
         $column($qb, $rule);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FilterRule $rule, $column)
    {
        return $column instanceof \Closure;
    }
}
