<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Filter\User;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule;

class UserFilter extends Filter
{
    protected function configure()
    {
        $this
            ->add(new Rule\FilterRuleText('name'))
        ;
    }
}
