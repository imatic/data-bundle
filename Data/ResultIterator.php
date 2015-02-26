<?php

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
class ResultIterator implements Iterator
{
    /**
     * @var QueryObjectInterface
     */
    protected $queryObject;

    /**
     * @var ArrayDisplayCriteriaFactory
     */
    protected $displayCriteriaFactory;

    /**
     * @var array
     */
    protected $criteria;

    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @var QueryExecutorInterface
     */
    protected $queryExecutor;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var int
     */
    protected $count = 0;

    protected $cache = [];

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

    public function current()
    {
        return $this->cache[$this->position % $this->getLimit()];
    }

    public function key()
    {
        $this->position;
    }

    public function next()
    {
        $this->position++;
        if (floor(($this->position + 1) / $this->getLimit()) > $this->criteria['page'] && $this->valid()) {
            $this->loadNextPage();
        }
    }

    public function rewind()
    {
        $this->init();
    }

    public function valid()
    {
        return $this->position < $this->count;
    }

    protected function init()
    {
        $this->criteria['page'] = 0;
        $this->position = 0;
        $this->count = $this->queryExecutor->count($this->queryObject, $this->createDisplayCriteria());
        $this->loadNextPage();
    }

    protected function loadNextPage()
    {
        $this->criteria['page']++;
        $this->cache = $this->queryExecutor->execute($this->queryObject, $this->createDisplayCriteria());
    }

    /**
     * @return DisplayCriteriaInterface
     */
    protected function createDisplayCriteria()
    {
        $this->displayCriteriaFactory->setAttributes($this->criteria);

        return $this->displayCriteriaFactory->createCriteria([
            'filter' => clone $this->filter,
        ]);
    }

    protected function getLimit()
    {
        return $this->criteria['limit'];
    }

    protected function getPage()
    {
        return $this->criteria['page'];
    }
}
