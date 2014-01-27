<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface DisplayCriteriaInterface
{
    /**
     * @return Pager
     */
    public function getPager();

    /**
     * @return Filter
     */
    public function getFilter();

    /**
     * @return Sorter
     */
    public function getSorter();
}
