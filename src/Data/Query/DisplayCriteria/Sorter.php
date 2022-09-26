<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class Sorter implements SorterInterface
{
    /**
     * @var SorterRule[]
     */
    protected array $sorterRules;

    /**
     * @param SorterRule[] $sorterRules
     */
    public function __construct(array $sorterRules = [])
    {
        $this->setSorterRules($sorterRules);
    }

    public function hasSorterRules(): bool
    {
        return !empty($this->sorterRules);
    }

    public function isSorted(string $column): bool
    {
        return \array_key_exists($column, $this->sorterRules);
    }

    public function getDirection(string $column, bool $lowercase = false): string
    {
        if (!$this->isSorted($column)) {
            return $lowercase ? \strtolower(SorterRule::ASC) : SorterRule::ASC;
        }

        return $this->sorterRules[$column]->getDirection($lowercase);
    }

    public function getReverseDirection(string $column, bool $lowercase = false): string
    {
        if (!$this->isSorted($column)) {
            return $lowercase ? \strtolower(SorterRule::ASC) : SorterRule::ASC;
        }

        return $this->sorterRules[$column]->getReverseDirection($lowercase);
    }

    /**
     * Retrieve an external iterator.
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->sorterRules);
    }

    public function count(): int
    {
        return \count($this->sorterRules);
    }

    public function setSorterRules(array $sorterRules): void
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

    protected function addSorterRule(SorterRule $sorterRule): self
    {
        $this->sorterRules[$sorterRule->getColumn()] = $sorterRule;

        return $this;
    }
}
