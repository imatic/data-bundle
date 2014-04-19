<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class FilterRuleChoice extends FilterRule
{
    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_IN,
            FilterOperatorMap::OPERATOR_NOT_IN,
        ];
    }

    protected function validateValue($value)
    {
        return is_scalar($value) || (is_array($value) && count(array_filter($value, 'is_scalar')) == count($value));
    }

    protected function getDefaultFormType()
    {
        return 'choice';
    }
}
