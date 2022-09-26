<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

interface HandlerRepositoryInterface
{
    public function getHandler(CommandInterface $command): HandlerInterface;

    /**
     * @param CommandInterface|string $command
     */
    public function getBundleName($command): ?string;

    public function addBundleName(string $handlerId, ?string $bundleName): void;
}
