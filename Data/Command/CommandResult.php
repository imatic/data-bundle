<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class CommandResult
{
    /**
     * @var bool
     */
    private $success;

    /**
     * @var array
     */
    private $messages;

    /**
     * @param boolean $success
     * @param array $messages
     */
    public function __construct($success, array $messages = [])
    {
        $this->success = (bool)$success;
        $this->messages = (array)$messages;
    }

    public function isSuccessful()
    {
        return $this->success;
    }

    public function hasMessages()
    {
        return (bool)count($this->messages);
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
