<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class SoftDeleteQuery implements QueryObjectInterface
{
    private string $table;

    /**
     * @var int[]
     */
    private array $ids;

    /**
     * @param int|int[] $ids
     */
    public function __construct(string $table, $ids)
    {
        $this->table = $table;
        $this->ids = \is_array($ids) ? $ids : [$ids];
    }

    public function build(Connection $connection): QueryBuilder
    {
        $idsType = \count(\array_filter($this->ids, 'is_numeric')) === \count($this->ids)
            ? Connection::PARAM_INT_ARRAY
            : Connection::PARAM_STR_ARRAY;

        return $connection->createQueryBuilder()
            ->update($connection->quoteIdentifier($this->table), 't')
            ->set('deleted_at', 'NOW()')
            ->where('t.id IN(:id)')
            ->setParameter('id', $this->ids, $idsType);
    }
}
