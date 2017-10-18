<?php
namespace Imatic\Bundle\DataBundle\Data\Command;

trait CommandExecutorAwareTrait
{
    /**
     * @var CommandExecutorInterface
     */
    protected $commandExecutor;

    public function setCommandExecutor(CommandExecutorInterface $commandExecutor)
    {
        $this->commandExecutor = $commandExecutor;
    }
}
