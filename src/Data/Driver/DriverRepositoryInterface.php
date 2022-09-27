<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver;

use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;

interface DriverRepositoryInterface
{
    public function add(DriverInterface $driver): void;

    public function get(string $name): DriverInterface;

    /**
     * @throws UnsupportedQueryObjectException
     */
    public function getQueryExecutorFor(QueryObjectInterface $queryObject): QueryExecutorInterface;
}
