<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryData
{
    private $data;
    private $types;
    private $table;

    public function __construct($table, array $data, array $types)
    {
        $this->data = $data;
        $this->types = $types;
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
