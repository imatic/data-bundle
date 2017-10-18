<?php
namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandResultInterface
{
    /**
     * @return string
     */
    public function getMessagesAsString();

    /**
     * @return MessageInterface[]
     */
    public function getMessages();

    /**
     * @return bool
     */
    public function hasMessages();

    /**
     * @return bool
     */
    public function isSuccessful();

    /**
     * @return bool
     */
    public function hasException();

    /**
     * @return \Exception
     */
    public function getException();

    /**
     * @param string|null $exceptionClass
     *
     * @throws \Exception
     */
    public function throwException($exceptionClass = null);

    /**
     * @param MessageInterface $message
     */
    public function addMessage(MessageInterface $message);

    /**
     * @param MessageInterface[] $messages
     */
    public function addMessages(array $messages);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name);

    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value);
}
