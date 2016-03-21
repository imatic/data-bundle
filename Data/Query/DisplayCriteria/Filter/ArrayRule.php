<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class ArrayRule extends FilterRule
{
    protected function getDefaultFormType()
    {
        return 'imatic_array_rule';
    }

    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_IN,
        ];
    }

    protected function validateValue($value)
    {
        return is_array($value);
    }
}
