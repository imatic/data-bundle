<?php declare(strict_types=1);
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
