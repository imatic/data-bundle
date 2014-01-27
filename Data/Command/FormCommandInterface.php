<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface FormCommandInterface extends CommandInterface, ObjectIdentityAwareInterface, ParametersAwareInterface
{
    public function getData();
}
