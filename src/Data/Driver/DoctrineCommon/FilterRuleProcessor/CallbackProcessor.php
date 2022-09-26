<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CallbackProcessor extends AbstractFilterRuleProcessor
{
    public function process(object $qb, FilterRule $rule, $column): void
    {
        $column($qb, $rule);
    }

    public function supports(object $qb, FilterRule $rule, $column): bool
    {
        return parent::supports($qb, $rule, $column) && $column instanceof \Closure;
    }

    protected function processOneColumn($qb, FilterRule $rule, $column)
    {
    }
}
