<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface SortableQueryObjectInterface
{
    /**
     * @return array Where key is name of the sorter and value is field to sort by.
     */
    public function getSorterMap();

    /**
     * @return array Where key is name of the sorter and value is field to sort by.
     */
    public function getDefaultSort();
}
