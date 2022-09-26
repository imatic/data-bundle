<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextRule extends FilterRule
{
    protected function getDefaultOperators(): array
    {
        return [
            FilterOperatorMap::OPERATOR_EQUAL,
            FilterOperatorMap::OPERATOR_NOT_EQUAL,
            FilterOperatorMap::OPERATOR_CONTAINS,
            FilterOperatorMap::OPERATOR_NOT_CONTAINS,
            FilterOperatorMap::OPERATOR_EMPTY,
            FilterOperatorMap::OPERATOR_NOT_EMPTY,
        ];
    }

    protected function getDefaultOperator(): string
    {
        return FilterOperatorMap::OPERATOR_CONTAINS;
    }

    protected function validateValue($value): bool
    {
        return \is_string($value);
    }

    protected function getDefaultFormType(): string
    {
        return TextType::class;
    }
}
