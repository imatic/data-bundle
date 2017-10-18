<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BooleanRule extends FilterRule
{
    const YES = 'true';

    const NO = 'false';

    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_EQUAL,
            FilterOperatorMap::OPERATOR_EMPTY,
            FilterOperatorMap::OPERATOR_NOT_EMPTY,
        ];
    }

    protected function validateValue($value)
    {
        return \is_bool($value) || \is_string($value);
    }

    protected function getDefaultFormType()
    {
        return ChoiceType::class;
    }

    protected function getDefaultFormOptions()
    {
        $choices = [
            self::YES,
            self::NO,
        ];

        return [
            'translation_domain' => 'ImaticDataBundle',
            'choices' => \array_combine($choices, $choices),
        ];
    }
}
