<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface FilterableQueryObjectInterface
{
    /**
     * @return array
     */
    public function getFilterMap();
}