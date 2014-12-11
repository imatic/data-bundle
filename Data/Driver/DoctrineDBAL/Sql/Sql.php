<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Sql;

use Doctrine\DBAL\Connection;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class Sql
{
    public static function concat(array $args, Connection $connection)
    {
        switch ($connection->getDatabasePlatform()->getName()) {
            case 'sqlite':
                return implode(' || ', $args);
            default:
                return sprintf('CONCAT(%s)', implode(', ', $args));
        }
    }
}
