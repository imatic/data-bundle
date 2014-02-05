<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class Filter implements \IteratorAggregate, FilterInterface
{
    /**
     * @var FilterRule[]
     */
    protected $filterRules = array();

    /**
     * @param FilterRule[] $filterRules
     */
    public function __construct(array $filterRules = [])
    {
        $this->filterRules = $filterRules;
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->filterRules);
    }
}
