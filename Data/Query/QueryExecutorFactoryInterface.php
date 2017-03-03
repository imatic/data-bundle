<?php

namespace Imatic\Bundle\DataBundle\Data\Query;

use RuntimeException;

interface QueryExecutorFactoryInterface
{
    /**
     * @param string $connectionName
     *
     * @return QueryExecutorInterface
     *
     * @throws RuntimeException
     */
    public function createWithConnection($connectionName = null);
}
