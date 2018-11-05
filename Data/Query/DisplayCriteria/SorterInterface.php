<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface SorterInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return bool
     */
    public function hasSorterRules();

    /**
     * @param string $column
     *
     * @return bool
     */
    public function isSorted($column);

    /**
     * @param string $column
     * @param bool   $lowercase
     *
     * @return string
     */
    public function getDirection($column, $lowercase = false);

    /**
     * @param string $column
     * @param bool   $lowercase
     *
     * @return string
     */
    public function getReverseDirection($column, $lowercase = false);

    /**
     * @param SorterRule[] $sorterRules
     */
    public function setSorterRules(array $sorterRules);
}
