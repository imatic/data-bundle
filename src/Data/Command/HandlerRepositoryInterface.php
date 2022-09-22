<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

interface HandlerRepositoryInterface
{
    /**
     * @param CommandInterface $command
     *
     * @return HandlerInterface
     */
    public function getHandler(CommandInterface $command): HandlerInterface;

    /**
     * @param CommandInterface|string $command
     *
     * @return string|null
     */
    public function getBundleName($command): ?string;

    /**
     * @param string      $handlerId
     * @param string|null $bundleName
     */
    public function addBundleName(string $handlerId, ?string $bundleName);
}
