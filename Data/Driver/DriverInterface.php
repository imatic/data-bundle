<?php

namespace Imatic\Bundle\DataBundle\Data\Driver;

use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;

interface DriverInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return ObjectManagerInterface
     */
    public function getObjectManager();

    /**
     * @return QueryExecutorInterface
     */
    public function getQueryExecutor();

    /**
     * @return string
     */
    public function getQueryObjectClass();
}