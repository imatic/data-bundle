<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\FormBundle\Form\Type\DateTimeRangeType;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DateTimeRangeRule extends RangeRule
{
    public function __construct(string $name, array $options = [])
    {
        $this->type = 'datetime';

        parent::__construct($name, $options);
    }

    protected function getDefaultFormType(): string
    {
        return DateTimeRangeType::class;
    }

    protected function getDefaultFormOptions(): array
    {
        return [
            'translation_domain' => 'ImaticDataBundle',
        ];
    }
}
