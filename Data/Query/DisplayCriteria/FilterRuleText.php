<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class FilterRuleText extends FilterRule
{
    protected function validateValue($value)
    {
        return is_string($value) || is_array($value);
    }

    public static function getOperators()
    {
        return ['equal', 'not-equal', 'in', 'contains', 'empty'];
    }
}
