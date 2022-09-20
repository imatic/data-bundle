<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator as ImaticPaginator;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface as DoctrineORMQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaQueryBuilderDelegate;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as BaseQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;

class QueryExecutor implements QueryExecutorInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var DisplayCriteriaQueryBuilderDelegate
     */
    private $displayCriteriaQueryBuilder;

    public function __construct(EntityManagerInterface $entityManager, DisplayCriteriaQueryBuilderDelegate $displayCriteriaQueryBuilder)
    {
        $this->entityManager = $entityManager;
        $this->displayCriteriaQueryBuilder = $displayCriteriaQueryBuilder;
    }

    private function createPaginator(DoctrineORMQueryObjectInterface $queryObject, Query $query)
    {
        if ($queryObject instanceof ExperimentalOptimizationQueryObjectInterface) {
            return new ImaticPaginator($query, true);
        }

        return new Paginator($query, true);
    }

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
        $paginator = $this->createPaginator($queryObject, $query);

        return \count($paginator);
    }

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

    public function executeAndCount(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return [
            $this->execute($queryObject, $displayCriteria),
            $this->count($queryObject, $displayCriteria),
        ];
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
     * @param BaseQueryObjectInterface $queryObject
     * @param Query                    $query
     *
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
        } elseif ($queryObject instanceof ResultQueryObjectInterface && (null !== $query->getMaxResults() || null !== $query->getFirstResult())) {
            return \iterator_to_array($this->createPaginator($queryObject, $query));
        }

        return $query->getResult();
    }
}
