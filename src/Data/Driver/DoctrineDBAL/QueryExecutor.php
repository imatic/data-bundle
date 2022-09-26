<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Result;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface as DoctrineDBALQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer\ResultNormalizerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaQueryBuilderDelegate;
use Imatic\Bundle\DataBundle\Data\Query\NonUniqueResultException;
use Imatic\Bundle\DataBundle\Data\Query\NoResultException;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as BaseQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryExecutor implements QueryExecutorInterface
{
    private Connection $connection;
    private DisplayCriteriaQueryBuilderDelegate $displayCriteriaQueryBuilder;
    private ResultNormalizerInterface $resultNormalizer;

    public function __construct(Connection $connection, DisplayCriteriaQueryBuilderDelegate $displayCriteriaQueryBuilder, ResultNormalizerInterface $resultNormalizer)
    {
        $this->connection = $connection;
        $this->displayCriteriaQueryBuilder = $displayCriteriaQueryBuilder;
        $this->resultNormalizer = $resultNormalizer;
    }

    public function count(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null): int
    {
        if (!$queryObject instanceof DoctrineDBALQueryObjectInterface) {
            throw new UnsupportedQueryObjectException($queryObject, $this);
        }

        $qb = $queryObject->build($this->connection);

        if ($displayCriteria) {
            $this->displayCriteriaQueryBuilder->applyFilter($qb, $displayCriteria->getFilter(), $queryObject);
        }

        $count = '1';

        $groupByPart = $qb->getQueryPart('groupBy');
        if ($groupByPart) {
            $count = \sprintf('DISTINCT(%s)', \implode(', ', $groupByPart));
            $qb->resetQueryPart('groupBy');
        }

        $qb->resetQueryPart('orderBy');

        return $qb->select(\sprintf('COUNT(%s)', $count))->executeQuery()->fetchOne();
    }

    public function execute(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if (!$queryObject instanceof DoctrineDBALQueryObjectInterface) {
            throw new UnsupportedQueryObjectException($queryObject, $this);
        }

        $qb = $queryObject->build($this->connection);

        if ($displayCriteria) {
            $this->displayCriteriaQueryBuilder->apply($qb, $queryObject, $displayCriteria);
        }

        if ($queryObject instanceof ResultQueryObjectInterface) {
            return $this->getResult($queryObject, $qb->executeQuery());
        }

        return $qb->executeStatement();
    }

    public function executeAndCount(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null): array
    {
        return [
            $this->execute($queryObject, $displayCriteria),
            $this->count($queryObject, $displayCriteria),
        ];
    }

    /**
     * @throws Exception
     */
    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    /**
     * @throws Exception
     */
    public function commit(): void
    {
        $this->connection->commit();
    }

    /**
     * @throws Exception
     */
    public function rollback(): void
    {
        $this->connection->rollback();
    }

    /**
     * @return mixed
     */
    private function getResult(BaseQueryObjectInterface $queryObject, Result $result)
    {
        $result = $this->resultNormalizer->normalize($queryObject, $result);

        if ($queryObject instanceof SingleScalarResultQueryObjectInterface) {
            return $this->getSingleScalarResult($result);
        } elseif ($queryObject instanceof SingleResultQueryObjectInterface) {
            return $this->getSingleResult($result);
        }

        return $result;
    }

    /**
     * @param mixed[] $result
     *
     * @return false|mixed
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    private function getSingleScalarResult(array $result)
    {
        if (\count($result) === 1 && \count($result[0]) === 1) {
            return \reset($result[0]);
        }

        if (!\count($result)) {
            throw new NoResultException();
        }

        throw new NonUniqueResultException();
    }

    /**
     * @param mixed[] $result
     *
     * @return mixed|null
     *
     * @throws NonUniqueResultException
     */
    private function getSingleResult(array $result)
    {
        if (\count($result) === 1) {
            return $result[0];
        }

        if (!\count($result)) {
            return null;
        }

        throw new NonUniqueResultException();
    }
}
