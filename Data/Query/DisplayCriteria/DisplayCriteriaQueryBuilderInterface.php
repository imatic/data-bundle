<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * @author Pavel Batecko <pavel.batecko@imatic.cz>
 */
interface DisplayCriteriaQueryBuilderInterface
{
    /**
     * @param object $qb
     *
     * @return bool
     */
    public function supports($qb);

    /**
     * @param object         $qb
     * @param PagerInterface $pager
     */
    public function applyPager($qb, PagerInterface $pager);

    /**
     * @param object          $qb
     * @param SorterInterface $sorter
     * @param array           $sorterMap
     */
    public function applySorter($qb, SorterInterface $sorter, array $sorterMap);
}
