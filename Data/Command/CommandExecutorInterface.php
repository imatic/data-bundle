<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandExecutorInterface
{
    /**
     * @param  CommandInterface       $command
     * @return CommandResultInterface
     */
    public function execute(CommandInterface $command);
}
