<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface HandlerRepositoryInterface
{
    /**
     * @param  string           $handlerName
     * @param  HandlerInterface $handler
     * @return void
     */
    public function addHandler($handlerName, HandlerInterface $handler);

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers();

    /**
     * @param  CommandInterface $command
     * @return HandlerInterface
     */
    public function getHandler(CommandInterface $command);
}
