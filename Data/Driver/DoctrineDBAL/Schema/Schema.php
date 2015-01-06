<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\Sequence;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class Schema
{
    /** @var AbstractSchemaManager */
    private $schemaManager;

    /** @var Connection */
    private $connection;

    /** @var array */
    private $overwrittenColumnTypes = [];

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

        $allColumnTypes = $this->getColumnTypes($table);
        $columnTypes = [];
        foreach ($columns as $column) {
            if (array_key_exists($column->getName(), $data)) {
                $columnTypes[$this->connection->quoteIdentifier($column->getName())] = $allColumnTypes[$column->getName()];
            }
        }

        $quotedData = [];
        foreach ($data as $column => $value) {
            $quotedColumn = $this->connection->quoteIdentifier($column);
            if (array_key_exists($quotedColumn, $columnTypes)) {
                $quotedData[$quotedColumn] = $value;
            }
        }

        ksort($quotedData);
        ksort($columnTypes);

        return new QueryData(
            $this->connection->quoteIdentifier($table),
            $quotedData,
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

        if (!isset($this->overwrittenColumnTypes[$table])) {
            return $columnTypes;
        }

        return array_merge($columnTypes, $this->overwrittenColumnTypes[$table]);
    }

    public function getNextIdValue($tableName)
    {
        $sequence = $this->findAutoincrementSequence($tableName);

        if (!$sequence) {
            return null;
        }

        $sql = $this->connection->getSchemaManager()->getDatabasePlatform()->getSequenceNextValSQL($sequence->getName());
        $statement = $this->connection->executeQuery($sql);

        return $statement->fetchColumn();
    }

    /**
     * @param string $tableName
     * @return Sequence|null
     */
    private function findAutoincrementSequence($tableName)
    {
        if (!$this->connection->getDatabasePlatform()->supportsSequences()) {
            return null;
        }

        $table = $this->findTableByName($tableName);
        $pkColumns = $table->getPrimaryKey()->getColumns();

        if (count($pkColumns) !== 1) {
            return;
        }

        $tableSequenceName = sprintf('%s_%s_seq', $table->getName(), $pkColumns[0]);
        $sequences = $this->connection->getSchemaManager()->listSequences();
        foreach ($sequences as $sequence) {
            if ($tableSequenceName === $sequence->getName()) {
                return $sequence;
            }
        }
    }

    /**
     * @param string $tableName
     *
     * @return Table
     * @throws \InvalidArgumentException
     */
    private function findTableByName($tableName)
    {
        $tables = $this->schemaManager->listTables();
        foreach ($tables as $table) {
            if ($table->getName() === $tableName) {
                return $table;
            }
        }

        throw new \InvalidArgumentException(sprintf('Table with name "%s" does not exists.', $tableName));
    }

    public function overwriteColumnTypes(array $overwrittenColumnTypes = [])
    {
        $this->overwrittenColumnTypes = $overwrittenColumnTypes;
    }
}
