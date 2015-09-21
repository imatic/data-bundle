<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface HandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command);
}
