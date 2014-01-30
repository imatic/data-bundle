<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface SorterInterface extends \IteratorAggregate
{
    /**
     * @return boolean
     */
    public function hasSorterRules();

    /**
     * @param string $column
     * @return bool
     */
    public function isSorted($column);

    /**
     * @param string $column
     * @param bool $lowercase
     * @return string
     */
    public function getDirection($column, $lowercase = false);

    /**
     * @param string $column
     * @param bool $lowercase
     * @return string
     */
    public function getReverseDirection($column, $lowercase = false);
}
