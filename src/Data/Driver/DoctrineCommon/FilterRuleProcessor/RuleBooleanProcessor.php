<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\BooleanRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class RuleBooleanProcessor extends AbstractFilterRuleProcessor
{
    protected function processOneColumn($qb, FilterRule $rule, $column)
    {
        if (FilterOperatorMap::OPERATOR_EMPTY === $rule->getOperator()) {
            return $qb->expr()->isNull($column);
        }

        switch ($rule->getValue()) {
            case BooleanRule::NO:
            case false:
                return $qb->expr()->eq($column, 'false');
            default:
                return $qb->expr()->eq($column, 'true');
        }
    }

    public function supports($qb, FilterRule $rule, $column)
    {
        return parent::supports($qb, $rule, $column) && $rule instanceof BooleanRule;
    }
}
