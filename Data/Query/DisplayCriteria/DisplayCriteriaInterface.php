<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface DisplayCriteriaInterface
{
    /**
     * @return PagerInterface
     */
    public function getPager();

    /**
     * @return FilterInterface
     */
    public function getFilter();

    /**
     * @return SorterInterface
     */
    public function getSorter();
}
