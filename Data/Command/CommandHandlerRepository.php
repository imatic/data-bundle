<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

use Imatic\Bundle\DataBundle\Exception\CommandHandlerNotFoundException;

class CommandHandlerRepository implements CommandHandlerRepositoryInterface
{
    /**
     * @var CommandHandlerInterface[]
     */
    private $commandHandlers;

    public function __construct()
    {
        $this->commandHandlers = [];
    }

    /**
     * {@inheritdoc}
     */
    public function addCommandHandler($name, CommandHandlerInterface $commandHandler)
    {
        $this->commandHandlers[$name] = $commandHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandHandlers()
    {
        return $this->commandHandlers;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandHandler(CommandInterface $command)
    {
        $name = $command->getHandlerName();
        if (array_key_exists($name, $this->commandHandlers)) {
            return $this->commandHandlers[$name];
        }
        throw new CommandHandlerNotFoundException($name);
    }
}
