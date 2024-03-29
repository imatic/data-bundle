<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class NotBetweenOperatorProcessor extends AbstractFilterRuleProcessor
{
    protected function processOneColumn($qb, FilterRule $rule, $column)
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

        return \call_user_func_array([$qb->expr(), 'orX'], $conditions);
    }

    public function supports(object $qb, FilterRule $rule, $column): bool
    {
        return
            parent::supports($qb, $rule, $column)
            && $rule->getOperator() === FilterOperatorMap::OPERATOR_NOT_BETWEEN;
    }
}
