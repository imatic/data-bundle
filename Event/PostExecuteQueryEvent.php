<?php

namespace Imatic\Bundle\DataBundle\Event;

use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\EventDispatcher\Event;

class PostExecuteQueryEvent extends Event
{
    /** @var QueryObjectInterface */
    protected $queryObject;

    /** @var mixed */
    protected $result;

    public function __construct(QueryObjectInterface $queryObject, $result)
    {
        $this->queryObject = $queryObject;
        $this->result = $result;
    }

    public function getQueryObject()
    {
        return $this->queryObject;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }
}
