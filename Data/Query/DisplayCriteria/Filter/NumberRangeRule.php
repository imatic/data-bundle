<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

class NumberRangeRule extends RangeRule
{
    protected function getDefaultFormType()
    {
        return 'number';
    }

    protected function validateValue($value)
    {
        return
            parent::validateValue($value)
            && (null === $value['start'] || is_numeric($value['start']))
            && (null === $value['end'] || is_numeric($value['end']))
        ;
    }
}
