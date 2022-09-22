<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandExecutorAwareInterface
{
    public function setCommandExecutor(CommandExecutorInterface $commandExecutor);
}
