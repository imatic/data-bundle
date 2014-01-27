<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface FormCommandInterface extends CommandInterface, ObjectIdentityAwareInterface, ParametersAwareInterface
{
    /**
     * @return array|object
     */
    public function getData();
}
