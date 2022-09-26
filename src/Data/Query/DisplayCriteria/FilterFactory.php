<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class FilterFactory implements ServiceSubscriberInterface
{
    protected ContainerInterface $locator;

    /**
     * @var string[]
     */
    protected array $filters;

    /**
     * @param ContainerInterface $locator
     */
    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
        $this->filters = [];
    }

    public static function getSubscribedServices(): array
    {
        return [];
    }

    /**
     * @throws \Throwable
     */
    public function create(string $name): FilterInterface
    {
        if ($this->locator->has($name)) {
            return $this->locator->get($name);
        }

        throw new \Exception(\sprintf('Filter "%s" was not found.', $name));
    }
}
