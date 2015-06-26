<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class TimeRangeRule extends RangeRule
{
    public function setValue($value)
    {
        parent::setValue($value);

        if ($this->bound) {
            $this->value['start']->setDate(1970, 1, 1);
            $this->value['end']->setDate(1970, 1, 1);
        }
    }

    protected function getDefaultFormType()
    {
        return 'imatic_type_time_range';
    }

    protected function getDefaultFormOptions()
    {
        return [
            'translation_domain' => 'ImaticDataBundle',
            'field_options' => [
                'timepicker' => true,
                'widget' => 'single_text',
            ],
        ];
    }
}
