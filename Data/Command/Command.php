<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

abstract class Command implements CommandInterface
{
    /**
     * @var string
     */
    private $commandHandlerName;

    public function __construct($commandHandlerName)
    {
        $this->commandHandlerName = $commandHandlerName;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlerName()
    {
        return $this->commandHandlerName;
    }

    /**
     * String representation of object
     *
     * @return string
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    /**
     * Constructs the object
     *
     * @param string $serialized
     * @return void
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }
}