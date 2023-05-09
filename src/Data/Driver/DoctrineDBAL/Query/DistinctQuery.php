<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DistinctQuery implements QueryObjectInterface, ResultQueryObjectInterface
{
    private string $table;
    private string $column;

    public function __construct(string $table, string $column)
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function build(Connection $connection): QueryBuilder
    {
        return $connection->createQueryBuilder()
            ->select(\sprintf('DISTINCT(%s.%s)', $this->getAlias(), $this->column))
            ->from($connection->quoteIdentifier($this->table), $this->getAlias());
    }

    private function getAlias(): string
    {
        return 't';
    }
}
