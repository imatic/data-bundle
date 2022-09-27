<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

abstract class RangeRule extends FilterRule
{
    protected function getDefaultOperators(): array
    {
        return [
            FilterOperatorMap::OPERATOR_BETWEEN,
            FilterOperatorMap::OPERATOR_NOT_BETWEEN,
        ];
    }

    protected function validateValue($value): bool
    {
        return
            \is_array($value)
            && 2 === \count($value)
            && \array_key_exists('start', $value)
            && \array_key_exists('end', $value);
    }

    public function isBound(): bool
    {
        return parent::isBound() &&
            \is_array($this->value) &&
            \array_filter($this->value, function ($val) {
                return $val !== null;
            });
    }
}
