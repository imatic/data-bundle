<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query;

use RuntimeException;

interface QueryExecutorFactoryInterface
{
    /**
     * @throws RuntimeException
     */
    public function createWithConnection(string $connectionName = null): QueryExecutorInterface;
}
