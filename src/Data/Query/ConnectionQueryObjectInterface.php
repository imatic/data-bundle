<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query;

interface ConnectionQueryObjectInterface
{
    public function getConnectionName(): string;
}
