<?php declare(strict_types=1);
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
