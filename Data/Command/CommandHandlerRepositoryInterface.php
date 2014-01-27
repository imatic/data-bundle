<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandHandlerRepositoryInterface
{
    /**
     * @param string $commandHandlerName
     * @param CommandHandlerInterface $commandHandler
     * @return void
     */
    public function addCommandHandler($commandHandlerName, CommandHandlerInterface $commandHandler);

    /**
     * @return CommandHandlerInterface[]
     */
    public function getCommandHandlers();

    /**
     * @param CommandInterface $command
     * @return CommandHandlerInterface
     */
    public function getCommandHandler(CommandInterface $command);
}
