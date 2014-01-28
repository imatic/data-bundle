<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandHandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return CommandResultInterface
     */
    public function handle(CommandInterface $command);
}
