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
        if ($this->hasHandler($name)) {
            throw new \LogicException(sprintf('Cannot register 2nd handler with name "%s".', $name));
        }

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
        if ($this->hasHandler($name)) {
            return $this->handlers[$name];
        }
        throw new HandlerNotFoundException($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function hasHandler($name)
    {
        return array_key_exists($name, $this->handlers);
    }
}
