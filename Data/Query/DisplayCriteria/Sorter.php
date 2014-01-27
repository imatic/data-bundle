<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class Sorter implements \IteratorAggregate
{
    /**
     * @var SorterRule[]
     */
    protected $sorterRules = array();

    /**
     * @param SorterRule[] $sorterRules
     */
    public function __construct(array $sorterRules = array())
    {
        foreach ($sorterRules as $sorterRule) {
            $this->addSorterRule($sorterRule);
        }
    }

    /**
     * @param SorterRule $sorterRule
     * @return $this
     */
    public function addSorterRule(SorterRule $sorterRule)
    {
        $this->sorterRules[$sorterRule->getColumn()] = $sorterRule;

        return $this;
    }

    public function hasSorterRules()
    {
        return !empty($this->sorterRules);
    }

    /**
     * @param string $column
     * @return bool
     */
    public function isSorted($column)
    {
        return array_key_exists((string)$column, $this->sorterRules);
    }

    /**
     * @param string $column
     * @param bool $lowercase
     * @return string
     */
    public function getDirection($column, $lowercase = false)
    {
        if (!$this->isSorted($column)) {
            return $lowercase ? strtolower(SorterRule::ASC) : SorterRule::ASC;
        }

        return $this->sorterRules[$column]->getDirection($lowercase);
    }

    /**
     * @param string $column
     * @param bool $lowercase
     * @return string
     */
    public function getReverseDirection($column, $lowercase = false)
    {
        if (!$this->isSorted($column)) {
            return $lowercase ? strtolower(SorterRule::ASC) : SorterRule::ASC;
        }

        return $this->sorterRules[$column]->getReverseDirection($lowercase);
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->sorterRules);
    }
}
