<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Form\Type\Filter\ArrayRuleType;

class ArrayRule extends FilterRule
{
    protected function getDefaultFormType()
    {
        return ArrayRuleType::class;
    }

    protected function getDefaultOperators()
    {
        return [
            FilterOperatorMap::OPERATOR_IN,
        ];
    }

    protected function validateValue($value)
    {
        return \is_array($value);
    }
}
