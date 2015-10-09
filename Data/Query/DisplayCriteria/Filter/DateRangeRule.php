<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

class DateRangeRule extends RangeRule
{
    public function setValue($value)
    {
        parent::setValue($value);
        
        if ($this->bound) {
            if ($this->value['start']) {
                $this->value['start']->setTime(0, 0, 0);
            }
            if ($this->value['end']) {
                $this->value['end']->setTime(23, 59, 59);
            }
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
        ];
    }

    protected function validateValue($value)
    {
        return
            parent::validateValue($value)
            && (null === $value['start'] || $value['start'] instanceof \DateTime)
            && (null === $value['end'] || $value['end'] instanceof \DateTime)
        ;
    }
}
