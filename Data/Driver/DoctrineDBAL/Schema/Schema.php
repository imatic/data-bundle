<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema;

use Doctrine\DBAL\Schema\AbstractSchemaManager;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class Schema
{
    /** @var AbstractSchemaManager */
    private $schemaManager;

    public function __construct(AbstractSchemaManager $schemaManager)
    {
        $this->schemaManager = $schemaManager;
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

        return new QueryData($table, $data, array_values($columnTypes));
    }
}
