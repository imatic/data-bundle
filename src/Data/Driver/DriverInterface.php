<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver;

use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;

interface DriverInterface
{
    public function getName(): string;

    public function getObjectManager(): ?ObjectManagerInterface;

    public function getQueryExecutor(): QueryExecutorInterface;

    public function getQueryObjectClass(): string;
}
