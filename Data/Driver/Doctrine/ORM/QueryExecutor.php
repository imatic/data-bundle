<?php

namespace Imatic\Bundle\DataBundle\Driver\Doctrine\ORM;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Doctrine\ORM\EntityManager;

class QueryExecutor implements QueryExecutorInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function count(QueryObjectInterface $queryObject)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function find(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(QueryObjectInterface $queryObject)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function execute(QueryObjectInterface $queryObject)
    {
    }
}
