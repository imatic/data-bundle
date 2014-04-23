<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class FilterRuleBoolean extends FilterRule
{
    const YES = 'yes';

    const NO = 'no';

    const YES_NO = 'yesNo';

    public function setValue($value)
    {
        return parent::setValue($value);
    }

    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_EQUAL,
            FilterOperatorMap::OPERATOR_EMPTY,
        ];
    }

    protected function validateValue($value)
    {
        return is_bool($value) || is_string($value) || is_null($value);
    }

    protected function getDefaultFormType()
    {
        return 'choice';
    }

    protected function getDefaultFormOptions()
    {
        $choices = [
            self::YES,
            self::NO,
            self::YES_NO,
        ];

        return [
            'translation_domain' => 'ImaticDataBundle',
            'choices' => array_combine($choices, $choices)
        ];
    }
}
