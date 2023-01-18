<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

interface ManagerQueryObjectInterface
{
    /**
     * The entity manager name used by query.
     */
    public function getManagerName(): string;
}
