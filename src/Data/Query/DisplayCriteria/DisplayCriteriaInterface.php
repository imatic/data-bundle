<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface DisplayCriteriaInterface
{
    public function getPager(): PagerInterface;

    public function getFilter(): FilterInterface;

    public function getSorter(): SorterInterface;
}
