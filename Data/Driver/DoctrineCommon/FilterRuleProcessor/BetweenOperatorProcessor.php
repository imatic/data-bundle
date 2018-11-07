<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class BetweenOperatorProcessor extends AbstractFilterRuleProcessor
{
    protected function processOneColumn($qb, FilterRule $rule, $column)
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

        return \call_user_func_array([$qb->expr(), 'andX'], $conditions);
    }

    public function supports($qb, FilterRule $rule, $column)
    {
        return
            parent::supports($qb, $rule, $column)
            && $rule->getOperator() === FilterOperatorMap::OPERATOR_BETWEEN;
    }
}
