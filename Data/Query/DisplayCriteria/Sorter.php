<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class Sorter implements SorterInterface
{
    /**
     * @var SorterRule[]
     */
    protected $sorterRules;

    /**
     * @param SorterRule[] $sorterRules
     */
    public function __construct(array $sorterRules = [])
    {
        $this->setSorterRules($sorterRules);
    }

    public function hasSorterRules()
    {
        return !empty($this->sorterRules);
    }

    /**
     * @param string $column
     *
     * @return bool
     */
    public function isSorted($column)
    {
        return \array_key_exists((string) $column, $this->sorterRules);
    }

    /**
     * @param string $column
     * @param bool   $lowercase
     *
     * @return string
     */
    public function getDirection($column, $lowercase = false)
    {
        if (!$this->isSorted($column)) {
            return $lowercase ? \strtolower(SorterRule::ASC) : SorterRule::ASC;
        }

        return $this->sorterRules[$column]->getDirection($lowercase);
    }

    /**
     * @param string $column
     * @param bool   $lowercase
     *
     * @return string
     */
    public function getReverseDirection($column, $lowercase = false)
    {
        if (!$this->isSorted($column)) {
            return $lowercase ? \strtolower(SorterRule::ASC) : SorterRule::ASC;
        }

        return $this->sorterRules[$column]->getReverseDirection($lowercase);
    }

    /**
     * Retrieve an external iterator.
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->sorterRules);
    }

    /**
     * @return int
     */
    public function count()
    {
        return \count($this->sorterRules);
    }

    /**
     * @param SorterRule[] $sorterRules
     */
    public function setSorterRules(array $sorterRules)
    {
        $this->sorterRules = [];
        foreach ($sorterRules as $key => $sorterRule) {
            if ($sorterRule instanceof SorterRule) {
                $this->addSorterRule($sorterRule);
            } else {
                $this->addSorterRule(new SorterRule($key, $sorterRule));
            }
        }
    }

    /**
     * @param SorterRule $sorterRule
     *
     * @return $this
     */
    protected function addSorterRule(SorterRule $sorterRule)
    {
        $this->sorterRules[$sorterRule->getColumn()] = $sorterRule;

        return $this;
    }
}
