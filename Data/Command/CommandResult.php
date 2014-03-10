<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class CommandResult implements CommandResultInterface
{
    /**
     * @var bool
     */
    private $success;

    /**
     * @var MessageInterface[]
     */
    private $messages;

    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @param  boolean $success
     * @param  MessageInterface[] $messages
     * @param  \Exception $exception
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

    public static function success($message = null, array $parameters = [])
    {
        $messages = [];
        if ($message) {
            $messages[] = new Message('error', $message, $parameters);
        }

        return new static(true, $messages);
    }

    public static function error($message = null, array $parameters = [])
    {
        $messages = [];
        if ($message) {
            $messages[] = new Message('error', $message, $parameters);
        }

        return new static(false, $messages);
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
