<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Iterator;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ResultIterator implements Iterator, \Countable
{
    protected QueryObjectInterface $queryObject;
    protected ArrayDisplayCriteriaFactory $displayCriteriaFactory;
    protected FilterInterface $filter;
    protected QueryExecutorInterface $queryExecutor;
    protected int $position = 0;
    protected int $count = 0;

    /**
     * @var array{page?: int, limit?: int}
     */
    protected array $criteria;

    /**
     * @var mixed[]
     */
    protected array $cache = [];

    /**
     * @param array{page?: int, limit?: int} $criteria
     */
    public function __construct(
        QueryObjectInterface $queryObject,
        ArrayDisplayCriteriaFactory $displayCriteriaFactory,
        FilterInterface $filter,
        QueryExecutorInterface $queryExecutor,
        array $criteria = []
    ) {
        $this->queryObject = $queryObject;
        $this->displayCriteriaFactory = $displayCriteriaFactory;
        $this->filter = $filter;
        $this->queryExecutor = $queryExecutor;

        if (!isset($criteria['limit'])) {
            $criteria['limit'] = 100;
        }
        $this->criteria = $criteria;
    }

    public function current(): mixed
    {
        return $this->cache[$this->position % $this->getLimit()];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;

        if ((($this->position + 1) / $this->getLimit()) > $this->criteria['page'] && $this->valid()) {
            $this->loadNextPage();
        }
    }

    public function rewind(): void
    {
        $this->init();
    }

    public function valid(): bool
    {
        return $this->position < $this->count;
    }

    protected function init(): void
    {
        $this->criteria['page'] = 0;
        $this->position = 0;
        $this->count = $this->queryExecutor->count($this->queryObject, $this->createDisplayCriteria());
        $this->loadNextPage();
    }

    protected function loadNextPage(): void
    {
        ++$this->criteria['page'];
        $this->cache = $this->queryExecutor->execute($this->queryObject, $this->createDisplayCriteria());
    }

    protected function createDisplayCriteria(): DisplayCriteriaInterface
    {
        $this->displayCriteriaFactory->setAttributes($this->criteria);

        return $this->displayCriteriaFactory->createCriteria([
            'filter' => clone $this->filter,
        ]);
    }

    protected function getLimit(): int
    {
        return $this->criteria['limit'];
    }

    protected function getPage(): int
    {
        return $this->criteria['page'];
    }

    public function count(): int
    {
        return $this->count;
    }
}
