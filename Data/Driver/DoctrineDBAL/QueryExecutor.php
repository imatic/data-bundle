<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\DisplayCriteriaQueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface as DoctrineDBALQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\NonUniqueResultException;
use Imatic\Bundle\DataBundle\Data\Query\NoResultException;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as BaseQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryExecutor implements QueryExecutorInterface
{
    /** @var Schema */
    private $schema;

    /** @var Connection */
    private $connection;

    /** @var DisplayCriteriaQueryBuilder */
    private $displayCriteriaQueryBuilder;

    public function __construct(Connection $connection, DisplayCriteriaQueryBuilder $displayCriteriaQueryBuilder, Schema $schema)
    {
        $this->connection = $connection;
        $this->displayCriteriaQueryBuilder = $displayCriteriaQueryBuilder;
        $this->schema = $schema;
    }

    public function count(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
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

    public function execute(BaseQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
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
     * @param  BaseQueryObjectInterface $queryObject
     * @param  PDOStatement         $statement
     * @return mixed
     */
    private function getResult(BaseQueryObjectInterface $queryObject, PDOStatement $statement)
    {
        $result = $this->getNormalizedResult($statement);

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

    private function getNormalizedResult(PDOStatement $statement)
    {
        $tables = [];
        preg_match('/FROM *"?(\w+)"?/i', $statement->queryString, $tables);

        if (count($tables) !== 2) {
            throw new \LogicException(sprintf('Found %d tables in queryString "%s", but 1 expected.', max([0, count($tables) - 1]), $statement->queryString));
        }

        $columnTypes = $this->schema->getColumnTypes($tables[1]);
        $platform = $this->connection->getSchemaManager()->getDatabasePlatform();

        $normalizedResult = [];
        $result = $statement->fetchAll();
        $resultCount = count($result);
        for ($i = 0; $i < $resultCount; $i++) {
            foreach ($result[$i] as $column => $value) {
                if (isset($columnTypes[$column])) {
                    $normalizedResult[$i][$column] = Type::getType($columnTypes[$column])->convertToPHPValue($value, $platform);
                } else {
                    $normalizedResult[$i][$column] = $value;
                }
            }
        }

        return $normalizedResult;
    }
}
