<?php
namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandExecutorAwareInterface
{
    public function setCommandExecutor(CommandExecutorInterface $commandExecutor);
}
