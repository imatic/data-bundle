<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class DisplayCriteria implements DisplayCriteriaInterface
{
    protected PagerInterface $pager;
    protected SorterInterface $sorter;
    protected FilterInterface $filter;

    public function __construct(PagerInterface $pager, SorterInterface $sorter, FilterInterface $filter)
    {
        $this->pager = $pager;
        $this->sorter = $sorter;
        $this->filter = $filter;
    }

    public function getPager(): PagerInterface
    {
        return $this->pager;
    }

    public function getSorter(): SorterInterface
    {
        return $this->sorter;
    }

    public function getFilter(): FilterInterface
    {
        return $this->filter;
    }
}
