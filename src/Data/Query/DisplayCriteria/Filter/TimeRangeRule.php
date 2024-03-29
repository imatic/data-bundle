<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\FormBundle\Form\Type\TimeRangeType;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class TimeRangeRule extends RangeRule
{
    public function setValue($value): self
    {
        parent::setValue($value);

        if ($this->isBound()) {
            $this->value['start']->setDate(1970, 1, 1);
            $this->value['end']->setDate(1970, 1, 1);
        }

        return $this;
    }

    protected function getDefaultFormType(): string
    {
        return TimeRangeType::class;
    }

    protected function getDefaultFormOptions(): array
    {
        return [
            'translation_domain' => 'ImaticDataBundle',
        ];
    }
}
