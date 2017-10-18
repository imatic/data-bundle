<?php
namespace Imatic\Bundle\DataBundle\Data;

interface ObjectManagerInterface
{
    public function flush();

    /**
     * @param object $object
     */
    public function persist($object);

    /**
     * @param object $object
     */
    public function remove($object);
}
