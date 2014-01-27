<?php

namespace Imatic\Bundle\DataBundle\Data;

interface ObjectManagerInterface
{
    public function flush();

    public function persist();

    public function remove();
}
