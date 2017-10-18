<?php
namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandExecutorInterface
{
    /**
     * @param CommandInterface $command
     *
     * @throws \Exception
     *
     * @return CommandResultInterface
     */
    public function execute(CommandInterface $command);
}
