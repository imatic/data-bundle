<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RecordIdQuery implements QueryObjectInterface, SingleResultQueryObjectInterface
{
    private string $table;

    /**
     * @var string[]
     */
    private array $columnValues;

    /**
     * @param string[] $columnValues
     */
    public function __construct(string $table, array $columnValues = [])
    {
        $this->table = $table;
        $this->columnValues = $columnValues;
    }

    public function build(Connection $connection): QueryBuilder
    {
        $qb = $connection->createQueryBuilder();
        $qb->select(\sprintf('%s.%s', $this->getAlias(), 'id'));
        $qb->from($connection->quoteIdentifier($this->table), $this->getAlias());
        $queryColumns = $this->getQueryColumns();

        $columnNames = \array_keys($queryColumns);
        foreach ($columnNames as $column) {
            $qb->andWhere($qb->expr()->eq($column, '?'));
        }
        $qb->setParameters(\array_values($queryColumns));

        return $qb;
    }

    /**
     * @return mixed[]
     */
    private function getQueryColumns(): array
    {
        $queryColumns = [];

        foreach ($this->columnValues as $column => $value) {
            $queryColumns[\sprintf('%s.%s', $this->getAlias(), $column)] = $value;
        }

        return $queryColumns;
    }

    private function getAlias(): string
    {
        return 't';
    }
}
