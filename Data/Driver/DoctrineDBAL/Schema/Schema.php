<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Connection;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class Schema
{
    /** @var AbstractSchemaManager */
    private $schemaManager;

    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->schemaManager = $connection->getSchemaManager();
    }

    /**
     * @param string $table
     * @param string $data
     * @return QueryData
     */
    public function getQueryData($table, array $data)
    {
        $columns = $this->schemaManager->listTableColumns($table);

        $columnTypes = [];
        foreach ($columns as $column) {
            if (isset($data[$column->getName()])) {
                $columnTypes[$column->getName()] = $column->getType()->getName();
            }
        }

        ksort($data);
        ksort($columnTypes);

        return new QueryData(
            $this->connection->quoteIdentifier($table),
            $data,
            array_values($columnTypes)
        );
    }

    /**
     * @param string $table
     *
     * @return array Associative array with columns as keys and type as it's values
     */
    public function getColumnTypes($table)
    {
        $columns = $this->schemaManager->listTableColumns($table);

        $columnTypes = [];
        foreach ($columns as $column) {
            $columnTypes[$column->getName()] = $column->getType()->getName();
        }

        return $columnTypes;
    }
}
