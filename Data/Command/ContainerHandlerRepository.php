<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerHandlerRepository implements HandlerRepositoryInterface
{
    /**
     * @var string[]
     * [
     *     handlerName => serviceId,
     *     ...
     * ]
     */
    private $handlers = [];

    /**
     * @var string[]
     * [
     *     handlerName => bundleName,
     *     ...
     * ]
     */
    private $bundles = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var HandlerRepositoryInterface
     */
    private $handlerRepository;

    public function __construct(ContainerInterface $container, HandlerRepositoryInterface $handlerRepository)
    {
        $this->container = $container;
        $this->handlerRepository = $handlerRepository;
    }

    public function addHandler($name, HandlerInterface $handler, $bundleName)
    {
        $this->handlerRepository->addHandler($name, $handler, $bundleName);
    }

    public function addLazyHandler($name, $handler, $bundleName)
    {
        if ($this->hasHandler($name)) {
            throw new \LogicException(sprintf('Cannot register 2nd handler with name "%s".', $name));
        }

        $this->handlers[$name] = $handler;
        $this->bundles[$name] = $bundleName;
    }

    public function getHandlers()
    {
        foreach ($this->handlers as $handlerName => $serviceId) {
            $this->handlerRepository->addHandler(
                $handlerName,
                $this->container->get($serviceId),
                $this->bundles[$handlerName]
            );
        }
        $this->handlers = [];
        $this->bundles = [];

        return $this->handlerRepository->getHandlers();
    }

    public function getHandler(CommandInterface $command)
    {
        $name = $command->getHandlerName();
        if ($this->hasUnresolvedHandler($name)) {
            $this->handlerRepository->addHandler(
                $name,
                $this->container->get($this->handlers[$name]),
                $this->bundles[$name]
            );
            unset($this->bundles[$name]);
            unset($this->handlers[$name]);
        }

        return $this->handlerRepository->getHandler($command);
    }

    /**
     * @param CommandInterface|string $command
     *
     * @return string
     */
    public function getBundleName($command)
    {
        if ($command instanceof CommandInterface) {
            $command = $command->getHandlerName();
        }

        return isset($this->bundles[$command])
            ? $this->bundles[$command]
            : $this->handlerRepository->getBundleName($command);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasHandler($name)
    {
        return $this->hasUnresolvedHandler($name) || $this->handlerRepository->hasHandler($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    private function hasUnresolvedHandler($name)
    {
        return array_key_exists($name, $this->handlers);
    }
}
