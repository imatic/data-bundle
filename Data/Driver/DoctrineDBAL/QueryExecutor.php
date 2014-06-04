<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\DisplayCriteriaQueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface as DoctrineDBALQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\NonUniqueResultException;
use Imatic\Bundle\DataBundle\Data\Query\NoResultException;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryExecutor implements QueryExecutorInterface
{
    /** @var Connection */
    private $connection;

    /** @var DisplayCriteriaQueryBuilder */
    private $displayCriteriaQueryBuilder;

    public function __construct(Connection $connection, DisplayCriteriaQueryBuilder $displayCriteriaQueryBuilder)
    {
        $this->connection = $connection;
        $this->displayCriteriaQueryBuilder = $displayCriteriaQueryBuilder;
    }

    public function count(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if (!$queryObject instanceof DoctrineDBALQueryObjectInterface) {
            throw new UnsupportedQueryObjectException($queryObject, $this);
        }

        $qb = $queryObject->build($this->connection);
        if ($displayCriteria) {
            $this->displayCriteriaQueryBuilder->applyFilter($qb, $displayCriteria->getFilter(), $queryObject);
        }

        /* @var $statement PDOStatement */
        $statement = $qb
            ->select('COUNT(1) count')
            ->execute()
        ;

        return $statement->fetch()['count'];
    }

    public function execute(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if (!$queryObject instanceof DoctrineDBALQueryObjectInterface) {
            throw new UnsupportedQueryObjectException($queryObject, $this);
        }

        $qb = $queryObject->build($this->connection);
        if ($displayCriteria) {
            $this->displayCriteriaQueryBuilder->apply($qb, $queryObject, $displayCriteria);
        }

        $statement = $qb->execute();

        return $this->getResult($queryObject, $statement);
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollback()
    {
        $this->connection->rollback();
    }

    /**
     * @param  QueryObjectInterface $queryObject
     * @param  PDOStatement         $statement
     * @return mixed
     */
    private function getResult(QueryObjectInterface $queryObject, PDOStatement $statement)
    {
        $result = $statement->fetchAll();

        if ($queryObject instanceof SingleScalarResultQueryObjectInterface) {
            return $this->getSingleScalarResult($result);
        } elseif ($queryObject instanceof SingleResultQueryObjectInterface) {
            return $this->getSingleResult($result);
        }

        return $result;
    }

    private function getSingleScalarResult($result)
    {
        if (count($result) === 1 && count($result[0]) === 1) {
            return reset($result[0]);
        }

        if (!count($result)) {
            throw new NoResultException();
        }

        throw new NonUniqueResultException();
    }

    private function getSingleResult($result)
    {
        if (count($result) === 1) {
            return $result[0];
        }

        if (!count($result)) {
            return null;
        }

        throw new NonUniqueResultException();
    }
}
