<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class ArrayDisplayCriteriaFactory extends DisplayCriteriaFactory
{
    private $attributes = [];

    public function setAttributes(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    protected function getAttribute($name, $default = null, $component = null, $persistent = true)
    {
        return array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }

    protected function clearAttribute($name, $component = null, $emptyValue = null)
    {
        if (null === $emptyValue) {
            unset($this->attributes[$name]);
        } else {
            $this->attributes[$name] = $emptyValue;
        }
    }
}
