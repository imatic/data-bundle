<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class Filter
 *
 * @todo: spatne, prepsat
 */
class Filter implements \IteratorAggregate
{

    protected $filterRules = array();

    protected $request;

    protected $prefix;

    public function __construct(Request $request = null)
    {
        if ($request) {
            $this->setRequest($request);
        }
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->filterRules);
    }
}
