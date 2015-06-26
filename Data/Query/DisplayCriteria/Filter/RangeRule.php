<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

abstract class RangeRule extends FilterRule
{
    public function setValue($value)
    {
        if (
            null !== $value
            && $this->validateValue($value)
            && isset($value['start'], $value['end'])
        ) {
            return parent::setValue($value);
        }
    }

    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_BETWEEN,
            FilterOperatorMap::OPERATOR_NOT_BETWEEN,
        ];
    }

    protected function validateValue($value)
    {
        if (!is_array($value) && 2 != count($value)) {
            return false;
        }
        if (!array_key_exists('start', $value) || !array_key_exists('end', $value)) {
            return false;
        }

        return true;
    }
}
