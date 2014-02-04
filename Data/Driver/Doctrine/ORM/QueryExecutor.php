<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class QueryExecutor implements QueryExecutorInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function count(QueryObjectInterface $queryObject)
    {
        $query = $this->getQuery($queryObject);
        $paginator = new Paginator($query, true);

        return count($paginator);
    }

    /**
     * {@inheritdoc}
     */
    public function find(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria)
    {
        return $this->getQuery($queryObject, $displayCriteria)->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(QueryObjectInterface $queryObject)
    {
        return $this->getQuery($queryObject)->setMaxResults(1)->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function execute(QueryObjectInterface $queryObject)
    {
        return $this->getQuery($queryObject)->execute();
    }

    /**
     * @param  QueryObjectInterface     $queryObject
     * @param  DisplayCriteriaInterface $displayCriteria
     * @return \Doctrine\ORM\Query
     */
    private function getQuery(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $queryObject->build($this->entityManager);

        if ($displayCriteria) {
            $this->applyPager($qb, $displayCriteria->getPager());
            $this->applyFilter($qb, $displayCriteria->getFilter());
            $this->applySorter($qb, $displayCriteria->getSorter());
        }

        return $qb->getQuery();
    }

    /**
     * @param QueryBuilder   $qb
     * @param PagerInterface $pager
     */
    private function applyPager(QueryBuilder $qb, PagerInterface $pager)
    {
        $qb
            ->setFirstResult($pager->getOffset())
            ->setMaxResults($pager->getLimit())
        ;
    }

    /**
     * @param QueryBuilder    $qb
     * @param FilterInterface $filter
     */
    private function applyFilter(QueryBuilder $qb, FilterInterface $filter)
    {

    }

    /**
     * @param QueryBuilder    $qb
     * @param SorterInterface $sorter
     */
    private function applySorter(QueryBuilder $qb, SorterInterface $sorter)
    {

    }
}
