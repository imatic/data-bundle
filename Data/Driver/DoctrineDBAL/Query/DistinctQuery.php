<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DistinctQuery implements QueryObjectInterface
{
    private $table;
    private $column;

    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function build(Connection $connection)
    {
        return $connection->createQueryBuilder()
            ->select(\sprintf('DISTINCT(%s.%s)', $this->getAlias(), $this->column))
            ->from($connection->quoteIdentifier($this->table), $this->getAlias());
    }

    private function getAlias()
    {
        return 't';
    }
}
