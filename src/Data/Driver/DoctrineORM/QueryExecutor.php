<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
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
    private ManagerRegistry $managerRegistry;
    private DisplayCriteriaQueryBuilderDelegate $displayCriteriaQueryBuilder;

    public function __construct(ManagerRegistry $managerRegistry, DisplayCriteriaQueryBuilderDelegate $displayCriteriaQueryBuilder)
    {
        $this->managerRegistry = $managerRegistry;
        $this->displayCriteriaQueryBuilder = $displayCriteriaQueryBuilder;
    }

    private function createPaginator(DoctrineORMQueryObjectInterface $queryObject, Query $query): Paginator
    {
        if ($queryObject instanceof ExperimentalOptimizationQueryObjectInterface) {
            return new ImaticPaginator($query, true);
        }

        return new Paginator($query, true);
    }

    public function count(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null): int
    {
        if (!($queryObject instanceof DoctrineORMQueryObjectInterface)) {
            throw new UnsupportedQueryObjectException($queryObject, $this);
        }

        $qb = $queryObject->build($this->getManager($queryObject));

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

        $qb = $queryObject->build($this->getManager($queryObject));

        if ($displayCriteria) {
            $this->displayCriteriaQueryBuilder->apply($qb, $queryObject, $displayCriteria);
        }

        return $this->getResult($queryObject, $qb->getQuery());
    }

    public function executeAndCount(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null): array
    {
        return [
            $this->execute($queryObject, $displayCriteria),
            $this->count($queryObject, $displayCriteria),
        ];
    }

    public function beginTransaction(): void
    {
        trigger_deprecation('imatic/data-bundle', '6.1', 'Method "%s()" is deprecated, use "%s::getManager()" instead.', __METHOD__, __CLASS__);

        $this->getManager()->beginTransaction();
    }

    public function commit(): void
    {
        trigger_deprecation('imatic/data-bundle', '6.1', 'Method "%s()" is deprecated, use "%s::getManager()" instead.', __METHOD__, __CLASS__);

        $this->getManager()->commit();
    }

    public function rollback(): void
    {
        trigger_deprecation('imatic/data-bundle', '6.1', 'Method "%s()" is deprecated, use "%s::getManager()" instead.', __METHOD__, __CLASS__);

        $this->getManager()->rollback();
    }

    public function getManager(BaseQueryObjectInterface $queryObject = null): EntityManager
    {
        $manager = $this->managerRegistry->getManager($this->getManagerName($queryObject));

        if (!$manager instanceof EntityManager) {
            throw new \RuntimeException(
                sprintf(
                    'Only managers of type "%s" are supported. Instance of "%s given.',
                    EntityManager::class,
                    \get_class($manager)
                )
            );
        }

        return $manager;
    }

    private function getManagerName(BaseQueryObjectInterface $queryObject = null): ?string
    {
        if ($queryObject instanceof ManagerQueryObjectInterface) {
            $name = $queryObject->getManagerName();
        }

        return $name ?? null;
    }

    /**
     * @return mixed
     */
    private function getResult(DoctrineORMQueryObjectInterface $queryObject, Query $query)
    {
        if ($queryObject instanceof SingleScalarResultQueryObjectInterface) {
            return $query->getSingleScalarResult();
        } elseif ($queryObject instanceof ScalarResultQueryObjectInterface) {
            return $query->getScalarResult();
        } elseif ($queryObject instanceof SingleResultQueryObjectInterface) {
            return $query->getOneOrNullResult();
        } elseif ($queryObject instanceof ResultQueryObjectInterface && (null !== $query->getMaxResults() || 0 !== $query->getFirstResult())) {
            return \iterator_to_array($this->createPaginator($queryObject, $query));
        }

        return $query->getResult();
    }
}
