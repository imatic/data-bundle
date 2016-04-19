<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\FormBundle\Form\Type\RangeType;

class NumberRangeRule extends RangeRule
{
    protected function getDefaultFormType()
    {
        return RangeType::class;
    }

    protected function getDefaultFormOptions()
    {
        return [
            'translation_domain' => 'ImaticDataBundle',
        ];
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
