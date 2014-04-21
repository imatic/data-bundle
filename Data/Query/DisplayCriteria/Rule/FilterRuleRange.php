<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class FilterRuleRange extends FilterRule
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
        if (!is_array($value) && 2 != count($value)) {
            return false;
        }
        if (!array_key_exists('start', $value) || !array_key_exists('end', $value)) {
            return false;
        }

        return true;
    }

    protected function getDefaultFormType()
    {
        return 'imatic_type_datetime_range';
    }

    protected function getDefaultFormOptions()
    {
        return [
            'field_options' => [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
//                'attr' => ['style' => 'width: 10em;']
            ]
        ];
    }
}
