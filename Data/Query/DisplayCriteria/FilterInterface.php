<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

interface FilterInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return FilterRule
     */
    public function getAt($index);
}
