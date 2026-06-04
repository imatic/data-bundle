<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Sql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\SQLitePlatform;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class Sql
{
    /**
     * @param mixed[] $args
     *
     * @throws Exception
     */
    public static function concat(array $args, Connection $connection): string
    {
        switch (\get_class($connection->getDatabasePlatform())) {
            case SQLitePlatform::class:
                return \implode(' || ', $args);
            default:
                return \sprintf('CONCAT(%s)', \implode(', ', $args));
        }
    }
}
