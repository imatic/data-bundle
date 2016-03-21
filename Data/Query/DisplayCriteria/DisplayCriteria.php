<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class DisplayCriteria implements DisplayCriteriaInterface
{
    /**
     * @var PagerInterface
     */
    protected $pager;

    /**
     * @var SorterInterface
     */
    protected $sorter;

    /**
     * @var FilterInterface
     */
    protected $filter;

    public function __construct(PagerInterface $pager, SorterInterface $sorter, FilterInterface $filter)
    {
        $this->pager = $pager;
        $this->sorter = $sorter;
        $this->filter = $filter;
    }

    public function getPager()
    {
        return $this->pager;
    }

    public function getSorter()
    {
        return $this->sorter;
    }

    public function getFilter()
    {
        return $this->filter;
    }
}
