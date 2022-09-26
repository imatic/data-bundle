<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Sequence;
use Doctrine\DBAL\Schema\Table;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class Schema
{
    private ?AbstractSchemaManager $schemaManager = null;
    private Connection $connection;

    /**
     * @var mixed[]
     */
    private array $overwrittenColumnTypes = [];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param mixed[] $data
     *
     * @throws Exception
     */
    public function getQueryData(string $table, array $data): QueryData
    {
        $columns = $this->getSchemaManager()->listTableColumns($table);

        $allColumnTypes = $this->getColumnTypes($table);
        $columnTypes = [];
        foreach ($columns as $column) {
            $columnName = $column->getName();
            if (\array_key_exists($columnName, $data)) {
                $columnTypes[$this->connection->quoteIdentifier($columnName)] = $allColumnTypes[$columnName];
            }
        }

        $quotedData = [];
        foreach ($data as $column => $value) {
            $quotedColumn = $this->connection->quoteIdentifier($column);
            if (\array_key_exists($quotedColumn, $columnTypes)) {
                $quotedData[$quotedColumn] = $value;
            }
        }

        \ksort($quotedData);
        \ksort($columnTypes);

        return new QueryData(
            $this->connection->quoteIdentifier($table),
            $quotedData,
            \array_values($columnTypes)
        );
    }

    /**
     * @return mixed[] Associative array with columns as keys and type as it's values
     *
     * @throws \Exception
     */
    public function getColumnTypes(string $table): array
    {
        $columns = $this->getSchemaManager()->listTableColumns($table);

        $columnTypes = [];
        foreach ($columns as $column) {
            $columnTypes[$column->getName()] = $column->getType()->getName();
        }

        if (!isset($this->overwrittenColumnTypes[$table])) {
            return $columnTypes;
        }

        return \array_merge($columnTypes, $this->overwrittenColumnTypes[$table]);
    }

    /**
     * @throws Exception
     */
    public function getNextIdValue(string $tableName): ?int
    {
        $sequence = $this->findAutoincrementSequence($tableName);

        if (!$sequence) {
            return null;
        }

        $sql = $this->connection->getDatabasePlatform()->getSequenceNextValSQL($sequence->getName());
        $statement = $this->connection->executeQuery($sql);

        return (int) $statement->fetchOne();
    }

    /**
     * @throws Exception
     */
    private function findAutoincrementSequence(string $tableName): ?Sequence
    {
        if (!$this->connection->getDatabasePlatform()->supportsSequences()) {
            return null;
        }

        $table = $this->findTableByName($tableName);
        $pkColumns = $table->getPrimaryKey()->getColumns();

        if (\count($pkColumns) !== 1) {
            return null;
        }

        $tableSequenceName = \sprintf('%s_%s_seq', $table->getName(), $pkColumns[0]);
        $sequences = $this->getSchemaManager()->listSequences();
        foreach ($sequences as $sequence) {
            if ($tableSequenceName === $sequence->getName()) {
                return $sequence;
            }
        }

        return null;
    }

    /**
     * @throws Exception
     * @throws \InvalidArgumentException
     */
    private function findTableByName(string $tableName): Table
    {
        $tables = $this->getSchemaManager()->listTables();
        foreach ($tables as $table) {
            if ($table->getName() === $tableName) {
                return $table;
            }
        }

        throw new \InvalidArgumentException(\sprintf('Table with name "%s" does not exists.', $tableName));
    }

    private function getSchemaManager(): AbstractSchemaManager
    {
        if (null === $this->schemaManager) {
            $this->schemaManager = $this->connection->createSchemaManager();
        }

        return $this->schemaManager;
    }

    /**
     * @param mixed[] $overwrittenColumnTypes
     */
    public function overwriteColumnTypes(array $overwrittenColumnTypes = []): void
    {
        $this->overwrittenColumnTypes = $overwrittenColumnTypes;
    }
}
