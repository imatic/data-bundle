<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as BaseQueryObjectInterface;

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
