<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface FilterableQueryObjectInterface
{
    /**
     * @return array Where key is name of the filter and value is field to filter by.
     */
    public function getFilterMap();
}
