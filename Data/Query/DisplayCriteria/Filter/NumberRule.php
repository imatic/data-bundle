<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class NumberRule extends FilterRule
{
    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_EQUAL,
            FilterOperatorMap::OPERATOR_NOT_EQUAL,
            FilterOperatorMap::OPERATOR_GREATER,
            FilterOperatorMap::OPERATOR_GREATER_EQUAL,
            FilterOperatorMap::OPERATOR_LESSER,
            FilterOperatorMap::OPERATOR_LESSER_EQUAL,
            FilterOperatorMap::OPERATOR_EMPTY,
            FilterOperatorMap::OPERATOR_NOT_EMPTY,
        ];
    }

    protected function validateValue($value)
    {
        return is_numeric($value);
    }

    protected function getDefaultFormType()
    {
        return 'number';
    }
}
