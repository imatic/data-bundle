<?php

namespace Imatic\Bundle\DataBundle\Data\Query;

use Imatic\Bundle\DataBundle\Event\DataEvents;
use Imatic\Bundle\DataBundle\Event\PostExecuteQueryEvent;
use Imatic\Bundle\DataBundle\Data\Driver\DriverRepositoryInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class QueryExecutor implements QueryExecutorInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var DriverRepositoryInterface
     */
    private $driverRepository;

    public function __construct(DriverRepositoryInterface $driverRepository, EventDispatcherInterface $dispatcher)
    {
        $this->driverRepository = $driverRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        $result = $this->driverRepository->getQueryExecutorFor($queryObject)->execute($queryObject, $displayCriteria);

        if ($this->dispatcher->hasListeners(DataEvents::POST_EXECUTE_QUERY)) {
            $event = new PostExecuteQueryEvent($result);
            $result = $event->getResult();
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function count(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return $this->driverRepository->getQueryExecutorFor($queryObject)->count($queryObject, $displayCriteria);
    }
}
