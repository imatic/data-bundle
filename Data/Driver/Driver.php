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
     * @var ObjectManagerInterface
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
        ObjectManagerInterface $objectManager,
        QueryExecutorInterface $queryExecutor,
        $queryObjectClass)
    {
        $this->name = $name;
        $this->objectManager = $objectManager;
        $this->queryExecutor = $queryExecutor;
        $this->queryObjectClass = $queryObjectClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryExecutor()
    {
        return $this->queryExecutor;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryObjectClass()
    {
        return $this->queryObjectClass;
    }
}