<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface HandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return CommandResultInterface
     */
    public function handle(CommandInterface $command);
}
