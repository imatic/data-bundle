<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\FormBundle\Form\Type\DateRangeType;

class DateRangeRule extends RangeRule
{
    public function __construct($name, array $options = array())
    {
        parent::__construct($name, $options);
        $this->type = 'date';
    }

    public function setValue($value)
    {
        parent::setValue($value);
        $this->updateValue();

        return $this;
    }

    public function ruleValue($value = null)
    {
        $args = func_get_args();
        $result = call_user_func_array('parent::ruleValue', $args);
        $this->updateValue();

        return $result;
    }

    private function updateValue()
    {
        if ($this->isBound()) {
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
        return DateRangeType::class;
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
