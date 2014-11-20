<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FilterFactory
{
    /** @var ContainerInterface */
    protected $container;

    /** @var string[] */
    protected $filters = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create($name)
    {
        if (!isset($this->filters[$name])) {
            throw new \Exception(sprintf('Filter "%s" was not found.', $name));
        }

        if (is_callable($this->filters[$name])) {
            return call_user_func($this->filters[$name], $name);
        }

        return $this->container->get($this->filters[$name]);
    }

    public function setFilters(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function addFilters(array $filters = [])
    {
        $this->filters = array_merge($this->filters, $filters);
    }
}
