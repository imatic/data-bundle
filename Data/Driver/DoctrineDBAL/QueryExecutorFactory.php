<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorFactoryInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QueryExecutorFactory implements QueryExecutorFactoryInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var array */
    private $queryExecutorCache = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createWithConnection($connectionName = null)
    {
        if ($connectionName !== null) {
            $connectionId = \sprintf('doctrine.dbal.%s_connection', $connectionName);
        } else {
            $connectionId = 'database_connection';
        }

        if (!isset($this->queryExecutorCache[$connectionId])) {
            if (!$this->container->has($connectionId)) {
                throw new RuntimeException(\sprintf('Cannot find service "%s".', $connectionId));
            }

            $this->queryExecutorCache[$connectionId] = new QueryExecutor(
                $this->container->get($connectionId),
                $this->container->get('imatic_data.display_criteria_query_builder'),
                $this->container->get('imatic_data.driver.doctrine_dbal.schema')
            );
        }

        return $this->queryExecutorCache[$connectionId];
    }
}
