<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

interface HandlerInterface
{
    /**
     * @param CommandInterface $command
     *
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command);
}
