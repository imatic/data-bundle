<?php

namespace Imatic\Bundle\DataBundle\Data\Driver;

use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\DriverNotFoundException;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;

class DriverRepository implements DriverRepositoryInterface
{
    /**
     * @var DriverInterface[]
     */
    private $drivers;

    public function __construct()
    {
        $this->drivers = [];
    }
    
    public function add(DriverInterface $driver)
    {
        $this->drivers[$driver->getName()] = $driver;
    }
    
    public function get($name)
    {
        if (!array_key_exists($name, $this->drivers)) {
            throw new DriverNotFoundException($name);
        }

        return $this->drivers[$name];
    }
    
    public function getQueryExecutorFor(QueryObjectInterface $queryObject)
    {
        foreach ($this->drivers as $driver) {
            $queryObjectClass = $driver->getQueryObjectClass();
            if ($queryObject instanceof $queryObjectClass) {
                return $driver->getQueryExecutor();
            }

        }

        throw new UnsupportedQueryObjectException($queryObject);
    }
}
