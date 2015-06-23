<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface as DoctrineORMQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as BaseQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\DisplayCriteriaQueryBuilder;

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
     * @param EntityManager               $entityManager
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
    public function count(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if (!($queryObject instanceof DoctrineORMQueryObjectInterface)) {
            throw new UnsupportedQueryObjectException($queryObject, $this);
        }

        $qb = $queryObject->build($this->entityManager);
        if ($displayCriteria) {
            $this->displayCriteriaQueryBuilder->applyFilter($qb, $displayCriteria->getFilter(), $queryObject);
        }

        $query = $qb->getQuery();
        $paginator = new Paginator($query, true);

        return count($paginator);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if (!($queryObject instanceof DoctrineORMQueryObjectInterface)) {
            throw new UnsupportedQueryObjectException($queryObject, $this);
        }

        $qb = $queryObject->build($this->entityManager);

        if ($displayCriteria) {
            $this->displayCriteriaQueryBuilder->apply($qb, $queryObject, $displayCriteria);
        }

        return $this->getResult($queryObject, $qb->getQuery());
    }

    public function beginTransaction()
    {
        $this->entityManager->beginTransaction();
    }

    public function commit()
    {
        $this->entityManager->commit();
    }

    public function rollback()
    {
        $this->entityManager->rollback();
    }

    /**
     * @param  BaseQueryObjectInterface $queryObject
     * @param  Query                    $query
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
            try {
                $paginator = new Paginator($query, true);

                return iterator_to_array($paginator);
            } catch (\Exception $ex) {
                /**
                 * This catch is here because of backwards compatibility.
                 * (so that exception is not thrown for results that may not be correctly paginated)
                 *
                 * @see https://github.com/KnpLabs/KnpPaginatorBundle/blob/master/Resources/doc/manual_counting.md
                 *
                 * There is no workaround to this limitation. But new interface with method "getQueryHints" could be created
                 */
                return $query->getResult();
            }
        }
    }
}
