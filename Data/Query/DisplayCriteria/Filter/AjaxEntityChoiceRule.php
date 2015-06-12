<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Doctrine\Common\Collections\ArrayCollection;

class AjaxEntityChoiceRule extends FilterRule
{
    /** @var string */
    private $class;
    /** @var string */
    private $route;

    public function __construct($name, $class, $route, array $options = [])
    {
        $this->class = $class;
        $this->route = $route;

        parent::__construct($name, $options);
    }

    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_IN,
            FilterOperatorMap::OPERATOR_NOT_IN,
        ];
    }

    protected function getDefaultFormType()
    {
        return 'imatic_type_ajax_entity_choice';
    }

    protected function getDefaultFormOptions()
    {
        return [
            'multiple' => true,
            'class' => $this->class,
            'route' => $this->route,
        ];
    }

    public function setValue($value)
    {
        parent::setValue($value);

        if (null !== $this->value && $this->value->count() < 1) {
            $this->reset();
        }
    }

    protected function validateValue($value)
    {
        return $value instanceof ArrayCollection;
    }

    public function getValue()
    {
        if (null !== $this->value) {
            $ids = [];

            foreach ($this->value as $entity) {
                $ids[] = $entity->getId();
            }

            return $ids;
        }
    }
}
