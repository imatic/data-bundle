<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandExecutorInterface
{
    /**
     * @param CommandInterface $command
     * @return void
     */
    public function execute(CommandInterface $command);
}
