<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChoiceRule extends FilterRule
{
    protected $multiple = false;

    protected $choices = [];

    public function __construct($name, array $choices, $multiple = false, $type = null, array $options = [])
    {
        parent::__construct($name, $options);
        $this->choices = $choices;
        $this->multiple = $multiple;
        if (null !== $type) {
            $this->formType = $type;
        }
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
        return
            is_scalar($value)
            || (
                is_array($value)
                && count(array_filter($value, 'is_scalar')) === count($value)
            )
        ;
    }

    protected function getDefaultFormType()
    {
        return ChoiceType::class;
    }

    protected function getDefaultFormOptions()
    {
        return [
            'choices' => &$this->choices,
            'multiple' => &$this->multiple,
        ];
    }
}
