<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

use Imatic\Bundle\DataBundle\Exception\HandlerNotFoundException;

class HandlerRepository implements HandlerRepositoryInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers;

    private $bundles;

    public function __construct()
    {
        $this->handlers = [];
        $this->bundles = [];
    }

    public function addHandler($name, HandlerInterface $handler, $bundleName)
    {
        if ($this->hasHandler($name)) {
            throw new \LogicException(sprintf('Cannot register 2nd handler with name "%s".', $name));
        }

        $this->handlers[$name] = $handler;
        $this->bundles[$name] = $bundleName;
    }

    public function getHandlers()
    {
        return $this->handlers;
    }

    public function getHandler(CommandInterface $command)
    {
        $name = $command->getHandlerName();
        if ($this->hasHandler($name)) {
            return $this->handlers[$name];
        }
        throw new HandlerNotFoundException($name);
    }

    /**
     * @param CommandInterface|string $command
     * @return string
     */
    public function getBundleName($command)
    {
        if ($command instanceof CommandInterface) {
            $command = $command->getHandlerName();
        }

        return $this->bundles[$command];
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
