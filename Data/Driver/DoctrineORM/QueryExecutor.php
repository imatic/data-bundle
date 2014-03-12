<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as BaseQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;

class QueryExecutor implements QueryExecutorInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var DisplayCriteriaQueryBuilder
     */
    private $displayCriteriaQueryBuilder;

    /**
     * @param EntityManager $entityManager
     * @param DisplayCriteriaQueryBuilder $displayCriteriaQueryBuilder
     */
    public function __construct(EntityManager $entityManager, DisplayCriteriaQueryBuilder $displayCriteriaQueryBuilder)
    {
        $this->entityManager = $entityManager;
        $this->displayCriteriaQueryBuilder = $displayCriteriaQueryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function count(BaseQueryObjectInterface $queryObject)
    {
        if (!($queryObject instanceof BaseQueryObjectInterface)) {
            throw new UnsupportedQueryObjectException($queryObject, $this);
        }

        $query = $this->getQuery($queryObject);
        $paginator = new Paginator($query, true);

        return count($paginator);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if (!($queryObject instanceof BaseQueryObjectInterface)) {
            throw new UnsupportedQueryObjectException($queryObject, $this);
        }

        return $this->getResult($queryObject, $this->getQuery($queryObject, $displayCriteria));
    }

    /**
     * @param BaseQueryObjectInterface $queryObject
     * @param Query $query
     * @return mixed
     */
    private function getResult(BaseQueryObjectInterface $queryObject, Query $query)
    {
        if ($queryObject instanceof SingleScalarResultQueryObjectInterface) {
            return $query->getSingleScalarResult();
        } elseif ($queryObject instanceof ScalarResultQueryObjectInterface) {
            return $query->getScalarResult();
        } elseif ($queryObject instanceof SingleResultQueryObjectInterface) {
            return $query->getOneOrNullResult();
        } else {
            return $query->getResult();
        }
    }

    /**
     * @param  BaseQueryObjectInterface $queryObject
     * @param  DisplayCriteriaInterface $displayCriteria
     * @return Query
     */
    private function getQuery(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        $qb = $queryObject->build($this->entityManager);

        if ($displayCriteria) {
            $this->displayCriteriaQueryBuilder->apply($qb, $displayCriteria);
        }

        return $qb->getQuery();
    }
}
