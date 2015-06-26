<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

class DateRangeRule extends RangeRule
{
    public function setValue($value)
    {
        parent::setValue($value);
        
        if ($this->bound) {
            $this->value['start']->setTime(0, 0, 0);
            $this->value['end']->setTime(23, 59, 59);
        }
    }

    protected function getDefaultFormType()
    {
        return 'imatic_type_date_range';
    }

    protected function getDefaultFormOptions()
    {
        return [
            'translation_domain' => 'ImaticDataBundle',
            'field_options' => [
                'datepicker' => true,
                'widget' => 'single_text',
            ],
        ];
    }
}
