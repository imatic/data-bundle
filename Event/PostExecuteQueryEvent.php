<?php

namespace Imatic\Bundle\DataBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class PostExecuteQueryEvent extends Event
{
    /** @var mixed */
    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
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
