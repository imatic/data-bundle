<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface ObjectIdentityAwareInterface
{
    /**
     * @return mixed
     */
    public function getObjectIdentity();
}