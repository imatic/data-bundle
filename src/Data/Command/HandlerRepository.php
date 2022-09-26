<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

use Imatic\Bundle\DataBundle\Exception\HandlerNotFoundException;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class HandlerRepository implements HandlerRepositoryInterface, ServiceSubscriberInterface
{
    private ContainerInterface $locator;

    /**
     * @var string[]
     * [
     *     handlerName => bundleName,
     *     ...
     * ]
     */
    private array $bundles;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
        $this->bundles = [];
    }

    public function getHandler(CommandInterface $command): HandlerInterface
    {
        $handlerName = $command->getHandlerName();

        if ($this->locator->has($handlerName)) {
            return $this->locator->get($handlerName);
        }

        throw new HandlerNotFoundException($handlerName);
    }

    public function getBundleName($command): ?string
    {
        if ($command instanceof CommandInterface) {
            $command = $command->getHandlerName();
        }

        return $this->bundles[$command];
    }

    public function addBundleName(string $handlerId, ?string $bundleName): void
    {
        $this->bundles[$handlerId] = $bundleName;
    }

    public static function getSubscribedServices(): array
    {
        return [];
    }
}
