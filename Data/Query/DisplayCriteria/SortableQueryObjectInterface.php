<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface SortableQueryObjectInterface
{
    /**
     * @return array
     */
    public function getSorterMap();

    /**
     * @return array
     */
    public function getDefaultSort();
}
