<?php
namespace Imatic\Bundle\DataBundle\Data\Driver;

use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;

interface DriverRepositoryInterface
{
    /**
     * @param DriverInterface $driver
     */
    public function add(DriverInterface $driver);

    /**
     * @param string $name
     *
     * @return DriverInterface
     */
    public function get($name);

    /**
     * @param QueryObjectInterface $queryObject
     *
     * @return QueryExecutorInterface
     *
     * @throws UnsupportedQueryObjectException
     */
    public function getQueryExecutorFor(QueryObjectInterface $queryObject);
}
