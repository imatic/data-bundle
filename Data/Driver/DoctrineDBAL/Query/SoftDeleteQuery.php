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
    private $id;

    public function __construct($table, $id)
    {
        $this->table = $table;
        $this->id = $id;
    }

    public function build(Connection $connection)
    {
        return $connection->createQueryBuilder()
            ->update($connection->quoteIdentifier($this->table), 't')
            ->set('deleted_at', 'NOW()')
            ->where('t.id = :id')
            ->setParameter('id', $this->id)
        ;
    }
}
