<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data;

interface ObjectManagerInterface
{
    public function flush(): void;

    public function persist(object $object): void;

    public function remove(object $object): void;
}
