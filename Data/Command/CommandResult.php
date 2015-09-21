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
    private $data;

    /**
     * @var MessageInterface[]
     */
    private $messages;

    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @param boolean            $success
     * @param MessageInterface[] $messages
     * @param \Exception         $exception
     * @throws \LogicException
     */
    public function __construct($success, array $messages = [], \Exception $exception = null)
    {
        if ($success && $exception) {
            throw new \LogicException('Result cannot be successful with exception.');
        }

        $this->success = (bool) $success;
        $this->messages = (array) $messages;
        $this->exception = $exception;
        $this->data = [];
    }

    public static function success($message = null, array $parameters = [])
    {
        $messages = [];
        if ($message) {
            $messages[] = new Message('success', $message, $parameters);
        }

        return new static(true, $messages);
    }

    public static function error($message = null, array $parameters = [], \Exception $exception = null)
    {
        $messages = [];
        if ($message) {
            $messages[] = new Message('error', $message, $parameters);
        }

        return new static(false, $messages, $exception);
    }

    public function isSuccessful()
    {
        return $this->success;
    }

    public function hasMessages()
    {
        return !empty($this->messages);
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function addMessage(MessageInterface $message)
    {
        $this->messages[] = $message;
    }

    public function addMessages(array $messages)
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }
    }

    public function hasException()
    {
        return $this->exception !== null;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function has($name)
    {
        return isset($this->data[$name]);
    }

    public function get($name, $default = null)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return $default;
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }
}
