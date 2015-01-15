<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Doctrine\Common\Persistence\ObjectManager as DoctrineObjectManager;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ResultIteratorFactory
{
    /**
     * @var ArrayDisplayCriteriaFactory
     */
    protected $displayCriteriaFactory;

    /**
     * @var FilterFactory
     */
    protected $filterFactory;

    /**
     * @var QueryExecutorInterface
     */
    protected $queryExecutor;

    /**
     * @var DoctrineObjectManager
     */
    protected $om;

    public function __construct(
        ArrayDisplayCriteriaFactory $displayCriteriaFactory,
        FilterFactory $filterFactory,
        QueryExecutorInterface $queryExecutor,
        DoctrineObjectManager $om
    ) {
        $this->displayCriteriaFactory = $displayCriteriaFactory;
        $this->filterFactory = $filterFactory;
        $this->queryExecutor = $queryExecutor;
        $this->om = $om;
    }

    public function create(QueryObjectInterface $queryObject, array $criteria = [])
    {
        if (!isset($criteria['filter_type'])) {
            throw new \LogicException('Filter type has to be specified!');
        }

        return new ResultIterator(
            $queryObject,
            $this->displayCriteriaFactory,
            $this->filterFactory->create($criteria['filter_type']),
            $this->queryExecutor,
            $this->om,
            $criteria
        );
    }
}
