<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\FormBundle\Form\Type\RangeType;

class NumberRangeRule extends RangeRule
{
    protected function getDefaultFormType(): string
    {
        return RangeType::class;
    }

    protected function getDefaultFormOptions(): array
    {
        return [
            'translation_domain' => 'ImaticDataBundle',
        ];
    }

    protected function validateValue($value): bool
    {
        return
            parent::validateValue($value)
            && (null === $value['start'] || \is_numeric($value['start']))
            && (null === $value['end'] || \is_numeric($value['end']));
    }
}
