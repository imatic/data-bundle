<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Doctrine\DBAL\Connection;

class InNotInOperatorProcessor extends AbstractFilterRuleProcessor
{
    public function process($qb, FilterRule $rule, $column)
    {
        $qb->andWhere($qb->expr()->{$rule->getOperator()}($column, $this->getQueryParameter($rule)));
        $qb->setParameter($this->getQueryParameterName($rule), $rule->getValue(), $this->getType($rule));
    }

    public function supports($qb, FilterRule $rule, $column)
    {
        return parent::supports($qb, $rule, $column) &&
            in_array($rule->getOperator(), [FilterOperatorMap::OPERATOR_IN, FilterOperatorMap::OPERATOR_NOT_IN]);
    }

    private function getType(FilterRule $rule)
    {
        $value = $rule->getValue();
        if ($rule->getType() || !is_array($value)) {
            return $rule->getType();
        }

        return count(array_filter($value, 'is_numeric')) === count($value)
            ? Connection::PARAM_INT_ARRAY
            : Connection::PARAM_STR_ARRAY;
    }
}
