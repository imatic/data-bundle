<?php
namespace Imatic\Bundle\DataBundle\Data\Driver;

use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;

class Driver implements DriverInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ObjectManagerInterface|null
     */
    private $objectManager;

    /**
     * @var QueryExecutorInterface
     */
    private $queryExecutor;

    /**
     * @var string
     */
    private $queryObjectClass;

    public function __construct(
        $name,
        QueryExecutorInterface $queryExecutor,
        $queryObjectClass,
        ObjectManagerInterface $objectManager = null
    ) {
        $this->name = $name;
        $this->objectManager = $objectManager;
        $this->queryExecutor = $queryExecutor;
        $this->queryObjectClass = $queryObjectClass;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }

    public function getQueryExecutor()
    {
        return $this->queryExecutor;
    }

    public function getQueryObjectClass()
    {
        return $this->queryObjectClass;
    }
}
