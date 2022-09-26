<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

trait CommandExecutorAwareTrait
{
    protected ?CommandExecutorInterface $commandExecutor = null;

    public function setCommandExecutor(CommandExecutorInterface $commandExecutor): void
    {
        $this->commandExecutor = $commandExecutor;
    }
}
