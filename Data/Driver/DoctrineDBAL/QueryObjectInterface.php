<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as BaseQueryObjectInterface;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
interface QueryObjectInterface extends BaseQueryObjectInterface
{
    /**
     * @param Connection $connection
     * @return QueryBuilder
     */
    public function build(Connection $connection);
}
