<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class DisplayCriteria implements DisplayCriteriaInterface
{
    /**
     * @var Pager
     */
    protected $pager;

    /**
     * @var Sorter
     */
    protected $sorter;

    /**
     * @var Filter
     */
    protected $filter;

    public function __construct(Pager $pager, Sorter $sorter, Filter $filter)
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