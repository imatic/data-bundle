<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;

class FilterFactory implements ServiceSubscriberInterface
{
    /** @var ContainerInterface */
    protected $locator;

    /** @var string[] */
    protected $filters;

    /**
     * @param ContainerInterface $locator
     */
    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
        $this->filters = [];
    }

    public static function getSubscribedServices()
    {
        return [];
    }

    /**
     * @param string $name
     *
     * @return object
     *
     * @throws \Exception
     */
    public function create(string $name)
    {
        if ($this->locator->has($name)) {
            return $this->locator->get($name);
        }

        throw new \Exception(\sprintf('Filter "%s" was not found.', $name));
    }
}
