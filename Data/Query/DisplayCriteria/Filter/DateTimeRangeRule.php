<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\FormBundle\Form\Type\DateTimeRangeType;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DateTimeRangeRule extends RangeRule
{
    public function __construct($name, array $options = array())
    {
        parent::__construct($name, $options);
        $this->type = 'datetime';
    }

    protected function getDefaultFormType()
    {
        return DateTimeRangeType::class;
    }

    protected function getDefaultFormOptions()
    {
        return [
            'translation_domain' => 'ImaticDataBundle',
        ];
    }
}
