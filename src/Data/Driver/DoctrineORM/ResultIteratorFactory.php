<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ResultIteratorFactory
{
    protected ArrayDisplayCriteriaFactory $displayCriteriaFactory;
    protected FilterFactory $filterFactory;
    protected QueryExecutor $queryExecutor;

    public function __construct(
        ArrayDisplayCriteriaFactory $displayCriteriaFactory,
        FilterFactory $filterFactory,
        QueryExecutor $queryExecutor
    ) {
        $this->displayCriteriaFactory = $displayCriteriaFactory;
        $this->filterFactory = $filterFactory;
        $this->queryExecutor = $queryExecutor;
    }

    /**
     * @param mixed[] $criteria
     */
    public function create(QueryObjectInterface $queryObject, array $criteria = [], FilterInterface $filter = null): ResultIterator
    {
        if (!$filter && \array_key_exists('filter_type', $criteria)) {
            $filter = $this->createFilter($criteria);
        }

        return new ResultIterator(
            $queryObject,
            $this->displayCriteriaFactory,
            $filter,
            $this->queryExecutor,
            $criteria
        );
    }

    /**
     * @param mixed[] $criteria
     */
    public function createFilter(array $criteria = []): FilterInterface
    {
        return $this->filterFactory->create($criteria['filter_type']);
    }
}
