<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

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
        return 'imatic_type_datetime_range';
    }

    protected function getDefaultFormOptions()
    {
        return [
            'translation_domain' => 'ImaticDataBundle',
        ];
    }
}
