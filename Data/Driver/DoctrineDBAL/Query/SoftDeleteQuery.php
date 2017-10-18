<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class SoftDeleteQuery implements QueryObjectInterface
{
    private $table;
    private $ids;

    public function __construct($table, $ids)
    {
        $this->table = $table;
        $this->ids = \is_array($ids) ? $ids : [$ids];
    }

    public function build(Connection $connection)
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
