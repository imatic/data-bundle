<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\ResultIterator;
use LogicException;

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

    public function __construct(
        ArrayDisplayCriteriaFactory $displayCriteriaFactory,
        FilterFactory $filterFactory,
        QueryExecutorInterface $queryExecutor
    ) {
        $this->displayCriteriaFactory = $displayCriteriaFactory;
        $this->filterFactory = $filterFactory;
        $this->queryExecutor = $queryExecutor;
    }

    public function create(QueryObjectInterface $queryObject, array $criteria, FilterInterface $filter = null)
    {
        if (!isset($criteria['filter_type'])) {
            throw new LogicException('Filter type has to be specified!');
        }

        return new ResultIterator(
            $queryObject,
            $this->displayCriteriaFactory,
            $filter ?: $this->createFilter($criteria),
            $this->queryExecutor,
            $criteria
        );
    }

    /**
     * @param array $criteria
     *
     * @return FilterInterface
     */
    public function createFilter(array $criteria)
    {
        return $this->filterFactory->create($criteria['filter_type']);
    }
}
