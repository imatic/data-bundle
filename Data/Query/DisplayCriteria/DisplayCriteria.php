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

    /**
     * {@inheritdoc}
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSorter()
    {
        return $this->sorter;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilter()
    {
        return $this->filter;
    }
}
