<?php

namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Filter\User;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter as FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

class UserFilter extends Filter
{
    protected function configure()
    {
        $this
            ->add(new FilterRule\NumberRule('id'))
            ->add(new FilterRule\TextRule('name'))
            ->add(new FilterRule\BooleanRule('activated'))
            ->add(new FilterRule\DateRangeRule('birthDate'))
            ->add(new FilterRule\DateRangeRule('favoriteDay'))
            ->add(new FilterRule\TimeRangeRule('favoriteTime'))
            ->add(new FilterRule\ChoiceRule('hairs', ['long', 'short']))
        ;
    }
}
