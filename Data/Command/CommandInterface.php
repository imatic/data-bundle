<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandInterface extends \Serializable
{
    /**
     * @return string The alias of a command handler service.
     */
    public function getHandlerName();
}
