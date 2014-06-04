<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Doctrine\DBAL\Query\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor\RuleProcessor;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as DoctrineDBALQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DisplayCriteriaQueryBuilder
{
    /** @var RuleProcessor */
    private $ruleProcessor;

    public function __construct(RuleProcessor $ruleProcessor)
    {
        $this->ruleProcessor = $ruleProcessor;
    }

    /**
     * @param QueryBuilder                    $qb
     * @param DoctrineDBALQueryObjectInterface $queryObject
     * @param DisplayCriteriaInterface        $displayCriteria
     */
    public function apply(QueryBuilder $qb, DoctrineDBALQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if ($displayCriteria === null) {
            return;
        }

        $this->applyPager($qb, $displayCriteria->getPager());
        $this->applyFilter($qb, $displayCriteria->getFilter(), $queryObject);
        $this->applySorter($qb, $displayCriteria->getSorter(), $queryObject);
    }

    /**
     * @param QueryBuilder   $qb
     * @param PagerInterface $pager
     */
    public function applyPager(QueryBuilder $qb, PagerInterface $pager)
    {
        if ($pager->isEnabled()) {
            $qb
                ->setFirstResult($pager->getOffset())
                ->setMaxResults($pager->getLimit());
        }
    }

    /**
     * @param  QueryBuilder                    $qb
     * @param  FilterInterface                 $filter
     * @param  DoctrineDBALQueryObjectInterface $queryObject
     * @throws \InvalidArgumentException
     */
    public function applyFilter(QueryBuilder $qb, FilterInterface $filter, DoctrineDBALQueryObjectInterface $queryObject)
    {
        if ($queryObject instanceof FilterableQueryObjectInterface) {
            $filterMap = $queryObject->getFilterMap();

            /* @var $filterRule FilterRule */
            foreach ($filter as $filterRule) {
                // Rule must be bound
                if (!$filterRule->isBound()) {
                    continue;
                }

                // Rule must be presented in filter map
                if (!isset($filterMap[$filterRule->getName()])) {
                    throw new \InvalidArgumentException(sprintf('Column "%s" is not presented in filter map', $filterRule->getName()));
                }

                $this->ruleProcessor->process($qb, $filterRule, $filterMap[$filterRule->getName()]);
            }
        }
    }

    /**
     * @param  QueryBuilder                    $qb
     * @param  SorterInterface                 $sorter
     * @param  DoctrineDBALQueryObjectInterface $queryObject
     * @throws \InvalidArgumentException
     */
    public function applySorter(QueryBuilder $qb, SorterInterface $sorter, DoctrineDBALQueryObjectInterface $queryObject)
    {
        if ($queryObject instanceof SortableQueryObjectInterface) {
            $sorterMap = $queryObject->getSorterMap();

            // Default sorting if no sorter rules exists
            if (0 === $sorter->count() && 0 < count($queryObject->getDefaultSort())) {
                $sorter->setSorterRules($queryObject->getDefaultSort());
            }

            /* @var $sorterRule SorterRule */
            foreach ($sorter as $sorterRule) {
                if (!isset($sorterMap[$sorterRule->getColumn()])) {
                    throw new \InvalidArgumentException(sprintf('Column "%s" is not presented in sorter map', $sorterRule->getColumn()));
                }

                $qb->addOrderBy($sorterMap[$sorterRule->getColumn()], $sorterRule->getDirection());
            }
        }
    }
}
