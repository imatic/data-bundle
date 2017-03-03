<?php

namespace Imatic\Bundle\DataBundle\Data\Query;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;

class QueryExecutorDelegate implements QueryExecutorInterface
{
    /** @var QueryExecutorFactoryInterface */
    private $queryExecutorFactory;

    public function __construct(QueryExecutorFactoryInterface $queryExecutorFactory)
    {
        $this->queryExecutorFactory = $queryExecutorFactory;
    }

    public function count(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return $this->getQueryExecutor($queryObject)->count($queryObject, $displayCriteria);
    }

    public function execute(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return $this->getQueryExecutor($queryObject)->execute($queryObject, $displayCriteria);
    }

    public function executeAndCount(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return $this->getQueryExecutor($queryObject)->executeAndCount($queryObject, $displayCriteria);
    }

    /**
     * @param QueryObjectInterface $queryObject
     *
     * @return QueryExecutorInterface
     */
    protected function getQueryExecutor(QueryObjectInterface $queryObject)
    {
        $connection = null;
        if ($queryObject instanceof ConnectionQueryObjectInterface) {
            $connection = $queryObject->getConnectionName();
        }

        return $this->queryExecutorFactory->createWithConnection($connection);
    }
}
