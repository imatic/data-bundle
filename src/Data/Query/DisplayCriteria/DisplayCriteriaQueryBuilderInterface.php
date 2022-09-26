<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * @author Pavel Batecko <pavel.batecko@imatic.cz>
 */
interface DisplayCriteriaQueryBuilderInterface
{
    public function supports(object $qb): bool;

    public function applyPager(object $qb, PagerInterface $pager): void;

    /**
     * @param array<string,string> $sorterMap
     */
    public function applySorter(object $qb, SorterInterface $sorter, array $sorterMap): void;
}
