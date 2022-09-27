<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\Persistence\ObjectManager as DoctrineObjectManager;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use LogicException;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ResultIteratorFactory
{
    protected ArrayDisplayCriteriaFactory $displayCriteriaFactory;
    protected FilterFactory $filterFactory;
    protected QueryExecutorInterface $queryExecutor;
    protected DoctrineObjectManager $om;

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

    /**
     * @param mixed[] $criteria
     */
    public function create(QueryObjectInterface $queryObject, array $criteria = [], FilterInterface $filter = null): ResultIterator
    {
        if (!isset($criteria['filter_type'])) {
            throw new LogicException('Filter type has to be specified!');
        }

        return new ResultIterator(
            $queryObject,
            $this->displayCriteriaFactory,
            $filter ?: $this->createFilter($criteria),
            $this->queryExecutor,
            $this->om,
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
