<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Sql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\SqlitePlatform;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class Sql
{
    public static function concat(array $args, Connection $connection)
    {
        switch (\get_class($connection->getDatabasePlatform())) {
            case SqlitePlatform::class:
                return \implode(' || ', $args);
            default:
                return \sprintf('CONCAT(%s)', \implode(', ', $args));
        }
    }
}
