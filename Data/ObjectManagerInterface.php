<?php

namespace Imatic\Bundle\DataBundle\Data;

interface ObjectManagerInterface
{
    /**
     * @return void
     */
    public function flush();

    /**
     * @param object $object
     * @return void
     */
    public function persist($object);

    /**
     * @param object $object
     * @return void
     */
    public function remove($object);
}
