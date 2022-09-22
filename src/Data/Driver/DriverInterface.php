<?php declare(strict_types=1);
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
     * @return ObjectManagerInterface|null
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
