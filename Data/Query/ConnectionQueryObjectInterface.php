<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query;

interface ConnectionQueryObjectInterface
{
    /**
     * @return string
     */
    public function getConnectionName();
}
