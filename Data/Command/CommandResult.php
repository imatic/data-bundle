<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class CommandResult implements CommandResultInterface
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
     * @var \Exception
     */
    private $exception;

    /**
     * @param boolean $success
     * @param array $messages
     * @param \Exception $exception
     * @throws \LogicException
     */
    public function __construct($success, array $messages = [], \Exception $exception = null)
    {
        if ($success && $exception) {
            throw new \LogicException('Result cannot be successful with exception.');
        }

        $this->success = (bool)$success;
        $this->messages = (array)$messages;
        $this->exception = $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return $this->success;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMessages()
    {
        return (bool)count($this->messages);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function hasException()
    {
        return $this->exception !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getException()
    {
        return $this->exception;
    }
}