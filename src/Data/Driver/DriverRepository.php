<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver;

use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\DriverNotFoundException;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryObjectException;

class DriverRepository implements DriverRepositoryInterface
{
    /**
     * @var DriverInterface[]
     */
    private array $drivers;

    public function __construct()
    {
        $this->drivers = [];
    }

    public function add(DriverInterface $driver): void
    {
        $this->drivers[$driver->getName()] = $driver;
    }

    public function get(string $name): DriverInterface
    {
        if (!\array_key_exists($name, $this->drivers)) {
            throw new DriverNotFoundException($name);
        }

        return $this->drivers[$name];
    }

    public function getQueryExecutorFor(QueryObjectInterface $queryObject): QueryExecutorInterface
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
