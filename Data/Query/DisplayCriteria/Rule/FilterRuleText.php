<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class FilterRuleText extends FilterRule
{
    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_EQUAL,
            FilterOperatorMap::OPERATOR_NOT_EQUAL,
            FilterOperatorMap::OPERATOR_CONTAINS,
            FilterOperatorMap::OPERATOR_NOT_CONTAINS,
            FilterOperatorMap::OPERATOR_EMPTY,
            FilterOperatorMap::OPERATOR_NOT_EMPTY,
        ];
    }

    protected function validateValue($value)
    {
        return is_string($value) || (is_array($value) && count(array_filter($value, 'is_string')) == count($value));
    }

    protected function getDefaultFormType()
    {
        return 'text';
    }
}
