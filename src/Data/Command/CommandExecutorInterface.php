<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandExecutorInterface
{
    /**
     * @throws \Exception
     */
    public function execute(CommandInterface $command): CommandResultInterface;
}
