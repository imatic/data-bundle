<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule;

class FilterRuleDateRange extends FilterRuleRange
{
    protected function getDefaultFormType()
    {
        return 'imatic_type_date_range';
    }

    protected function getDefaultFormOptions()
    {
        return [
            'translation_domain' => 'ImaticDataBundle',
            'field_options' => [
                'widget' => 'single_text',
                'datepicker' => true,
            ],
        ];
    }
}
