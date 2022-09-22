<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer\ResultNormalizerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaQueryBuilderDelegate;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QueryExecutorFactory implements QueryExecutorFactoryInterface
{
    private DisplayCriteriaQueryBuilderDelegate $delegate;
    private ResultNormalizerInterface $normalizer;
    private ContainerInterface $container;
    private array $queryExecutorCache = [];

    public function __construct(
        DisplayCriteriaQueryBuilderDelegate $delegate,
        ResultNormalizerInterface $resultNormalizer,
        ContainerInterface $container
    ) {
        $this->delegate = $delegate;
        $this->normalizer = $resultNormalizer;
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
                throw new \RuntimeException(\sprintf('Cannot find service "%s".', $connectionId));
            }

            $this->queryExecutorCache[$connectionId] = new QueryExecutor(
                $this->container->get($connectionId),
                $this->delegate,
                $this->normalizer,
            );
        }

        return $this->queryExecutorCache[$connectionId];
    }
}
