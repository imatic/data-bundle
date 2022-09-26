<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryData
{
    private string $table;

    /**
     * @var mixed[]
     */
    private array $data;

    /**
     * @var mixed[]
     */
    private array $types;

    /**
     * @param mixed[] $data
     * @param mixed[] $types
     */
    public function __construct(string $table, array $data, array $types)
    {
        $this->data = $data;
        $this->types = $types;
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return mixed[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }
}
