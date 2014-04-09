<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as DoctrineORMQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 * @author Stepan Koci <stepan.koci@imatic.cz>
 */
class DisplayCriteriaQueryBuilder
{
    private static $operatorMap = [
        'equal' => '=',
        'not-equal' => '<>',
        'in' => 'IN',
        'contains' => 'LIKE',
        'empty' => 'IS NULL',
    ];

    /**
     * @param QueryBuilder $qb
     * @param DoctrineORMQueryObjectInterface $queryObject
     * @param DisplayCriteriaInterface $displayCriteria
     */
    public function apply(QueryBuilder $qb, DoctrineORMQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if ($displayCriteria === null) {
            return;
        }

        $this->applyPager($qb, $displayCriteria->getPager());
        $this->applyFilter($qb, $displayCriteria->getFilter(), $queryObject);
        $this->applySorter($qb, $displayCriteria->getSorter(), $queryObject);
    }

    /**
     * @param QueryBuilder $qb
     * @param PagerInterface $pager
     */
    private function applyPager(QueryBuilder $qb, PagerInterface $pager)
    {
        $qb
            ->setFirstResult($pager->getOffset())
            ->setMaxResults($pager->getLimit());
    }

    /**
     * @param QueryBuilder $qb
     * @param FilterInterface $filter
     * @param DoctrineORMQueryObjectInterface $queryObject
     * @throws \InvalidArgumentException
     */
    private function applyFilter(QueryBuilder $qb, FilterInterface $filter, DoctrineORMQueryObjectInterface $queryObject)
    {
        if ($queryObject instanceof FilterableQueryObjectInterface) {
            $filterMap = $queryObject->getFilterMap();

            /* @var $filterRule FilterRule */
            foreach ($filter as $filterRule) {
                if (!isset($filterMap[$filterRule->getColumn()])) {
                    throw new \InvalidArgumentException(sprintf('Column "%s" is not presented in filter map', $filterRule->getColumn()));
                }

                $dqlPart = sprintf('%s %s %s',
                    $filterMap[$filterRule->getColumn()],
                    self::$operatorMap[$filterRule->getOperator()],
                    ':' . $filterRule->getColumn()
                );

                $qb->andWhere($dqlPart);
                $qb->setParameter($filterRule->getColumn(), $filterRule->getValue());
            }
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param SorterInterface $sorter
     * @param DoctrineORMQueryObjectInterface $queryObject
     * @throws \InvalidArgumentException
     */
    private function applySorter(QueryBuilder $qb, SorterInterface $sorter, DoctrineORMQueryObjectInterface $queryObject)
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
