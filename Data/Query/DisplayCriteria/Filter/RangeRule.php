<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

abstract class RangeRule extends FilterRule
{
    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_BETWEEN,
            FilterOperatorMap::OPERATOR_NOT_BETWEEN,
        ];
    }

    protected function validateValue($value)
    {
        return
            is_array($value)
            && 2 === count($value)
            && array_key_exists('start', $value)
            && array_key_exists('end', $value)
            && array_filter($value, function ($val) {
                return $val !== null;
            })
        ;
    }
}
