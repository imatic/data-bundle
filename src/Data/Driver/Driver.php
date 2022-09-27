<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver;

use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;

class Driver implements DriverInterface
{
    private string $name;
    private string $queryObjectClass;
    private QueryExecutorInterface $queryExecutor;
    private ?ObjectManagerInterface $objectManager;

    public function __construct(
        string $name,
        QueryExecutorInterface $queryExecutor,
        string $queryObjectClass,
        ObjectManagerInterface $objectManager = null
    ) {
        $this->name = $name;
        $this->objectManager = $objectManager;
        $this->queryExecutor = $queryExecutor;
        $this->queryObjectClass = $queryObjectClass;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getObjectManager(): ?ObjectManagerInterface
    {
        return $this->objectManager;
    }

    public function getQueryExecutor(): QueryExecutorInterface
    {
        return $this->queryExecutor;
    }

    public function getQueryObjectClass(): string
    {
        return $this->queryObjectClass;
    }
}
