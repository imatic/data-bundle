<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query;

interface NormalizeResultQueryObjectInterface
{
    /**
     * @return array<string,string>
     */
    public function getNormalizerMap(): array;
}
