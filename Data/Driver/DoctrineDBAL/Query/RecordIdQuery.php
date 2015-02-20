<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RecordIdQuery implements QueryObjectInterface, SingleResultQueryObjectInterface
{
    private $table;
    private $columnValues;

    public function __construct($table, array $columnValues = [])
    {
        $this->table = $table;
        $this->columnValues = $columnValues;
    }

    public function build(Connection $connection)
    {
        $qb = $connection->createQueryBuilder();
        $qb->select(sprintf('%s.%s', $this->getAlias(), 'id'));
        $qb->from($connection->quoteIdentifier($this->table), $this->getAlias());
        $queryColumns = $this->getQueryColumns();

        foreach ($queryColumns as $column => $value) {
            $qb->andWhere($qb->expr()->eq($column, '?'));
        }
        $qb->setParameters(array_values($queryColumns));

        return $qb;
    }

    private function getQueryColumns()
    {
        $queryColumns = [];
        foreach ($this->columnValues as $column => $value) {
            $queryColumns[sprintf('%s.%s', $this->getAlias(), $column)] = $value;
        }

        return $queryColumns;
    }

    private function getAlias()
    {
        return 't';
    }
}
