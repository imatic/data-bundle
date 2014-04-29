<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class ChoiceRule extends FilterRule
{
    protected $multiple = false;

    protected $choices = [];

    public function __construct($name, array $choices, $multiple = false, $type = null)
    {
        parent::__construct($name);
        $this->choices = $choices;
        $this->multiple = $multiple;
        $this->formType = $type;
    }

    public function setChoices(array $choices)
    {
        $this->choices = $choices;

        return $this;
    }

    public function setMultiple($bool = true)
    {
        $this->multiple = (bool) $bool;

        return $this;
    }

    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_IN,
            FilterOperatorMap::OPERATOR_NOT_IN,
        ];
    }

    protected function validateValue($value)
    {
        return is_scalar($value) || (is_array($value) && count(array_filter($value, 'is_scalar')) == count($value));
    }

    protected function getDefaultFormType()
    {
        return 'genemu_jqueryselect2_choice';
    }

    protected function getDefaultFormOptions()
    {
        return [
            'choices' => &$this->choices,
            'multiple' => &$this->multiple,
        ];
    }
}
