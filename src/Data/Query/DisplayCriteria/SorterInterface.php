<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface SorterInterface extends \IteratorAggregate, \Countable
{
    public function hasSorterRules(): bool;

    public function isSorted(string $column): bool;

    public function getDirection(string $column, bool $lowercase = false): string;

    public function getReverseDirection(string $column, bool $lowercase = false): string;

    /**
     * @param mixed[] $sorterRules
     */
    public function setSorterRules(array $sorterRules): void;
}
