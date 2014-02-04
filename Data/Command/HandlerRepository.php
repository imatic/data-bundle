<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

use Imatic\Bundle\DataBundle\Exception\HandlerNotFoundException;

class HandlerRepository implements HandlerRepositoryInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers;

    public function __construct()
    {
        $this->handlers = [];
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler($name, HandlerInterface $handler)
    {
        $this->handlers[$name] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler(CommandInterface $command)
    {
        $name = $command->getHandlerName();
        if (array_key_exists($name, $this->handlers)) {
            return $this->handlers[$name];
        }
        throw new HandlerNotFoundException($name);
    }
}
