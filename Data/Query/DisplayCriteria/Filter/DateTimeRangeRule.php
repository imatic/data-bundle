<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DateTimeRangeRule extends RangeRule
{
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
