<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Types\Type;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface as DoctrineDBALQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema\Schema;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaQueryBuilderDelegate;
use Imatic\Bundle\DataBundle\Data\Query\NonUniqueResultException;
use Imatic\Bundle\DataBundle\Data\Query\NoResultException;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as BaseQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleScalarResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryExecutor implements QueryExecutorInterface
{
    /** @var Schema */
    private $schema;

    /** @var Connection */
    private $connection;

    /** @var DisplayCriteriaQueryBuilderDelegate */
    private $displayCriteriaQueryBuilder;

    public function __construct(Connection $connection, DisplayCriteriaQueryBuilderDelegate $displayCriteriaQueryBuilder, Schema $schema)
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

        $count = '1';

        $groupByPart = $qb->getQueryPart('groupBy');
        if ($groupByPart) {
            $count = \sprintf('DISTINCT(%s)', \implode(', ', $groupByPart));
            $qb->resetQueryPart('groupBy');
        }

        /* @var $statement PDOStatement */
        $statement = $qb
            ->select(\sprintf('COUNT(%s) count', $count))
            ->execute();

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

        if (\is_scalar($statement)) {
            return $statement;
        }

        return $this->getResult($queryObject, $statement);
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
     * @param BaseQueryObjectInterface $queryObject
     * @param PDOStatement             $statement
     *
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
        if (\count($result) === 1 && \count($result[0]) === 1) {
            return \reset($result[0]);
        }

        if (!\count($result)) {
            throw new NoResultException();
        }

        throw new NonUniqueResultException();
    }

    private function getSingleResult($result)
    {
        if (\count($result) === 1) {
            return $result[0];
        }

        if (!\count($result)) {
            return null;
        }

        throw new NonUniqueResultException();
    }

    private function getNormalizedResult(PDOStatement $statement)
    {
        $tables = [];
        $quoteCharacter = $this->connection->getDatabasePlatform()->getIdentifierQuoteCharacter();
        \preg_match(\sprintf('/FROM *%1$s?(\w+)%1$s?/i', $quoteCharacter), $statement->queryString, $tables);

        if (\count($tables) !== 2) {
            throw new \LogicException(\sprintf('Found %d tables in queryString "%s", but 1 expected.', \max([0, \count($tables) - 1]), $statement->queryString));
        }

        $columnTypes = $this->schema->getColumnTypes($tables[1]);
        $platform = $this->connection->getSchemaManager()->getDatabasePlatform();

        $normalizedResult = [];
        $result = $statement->fetchAll();
        $resultCount = \count($result);
        for ($i = 0; $i < $resultCount; ++$i) {
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
