<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface HandlerRepositoryInterface
{
    /**
     * @param  string $handlerName
     * @param  HandlerInterface $handler
     * @param  string $bundleName
     * @return void
     */
    public function addHandler($handlerName, HandlerInterface $handler, $bundleName);

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers();

    /**
     * @param  CommandInterface $command
     * @return HandlerInterface
     */
    public function getHandler(CommandInterface $command);

    /**
     * @param CommandInterface|string $command
     * @return string
     */
    public function getBundleName($command);
}
