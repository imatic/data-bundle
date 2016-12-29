<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CallbackProcessor extends AbstractFilterRuleProcessor
{
    public function process($qb, FilterRule $rule, $column)
    {
        $column($qb, $rule);
    }

    public function supports($qb, FilterRule $rule, $column)
    {
        return
            parent::supports($qb, $rule, $column)
            && $column instanceof \Closure
        ;
    }

    protected function processOneColumn($qb, FilterRule $rule, $column)
    {
    }
}
