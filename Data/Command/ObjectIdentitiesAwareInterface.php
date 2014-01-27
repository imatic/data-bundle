<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface ObjectIdentitiesAwareInterface
{
    /**
     * @return mixed[]
     */
    public function getObjectIdentities();
}
