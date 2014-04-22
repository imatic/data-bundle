<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandResultInterface
{
    /**
     * @return MessageInterface[]
     */
    public function getMessages();

    /**
     * @return boolean
     */
    public function hasMessages();

    /**
     * @return boolean
     */
    public function isSuccessful();

    /**
     * @return boolean
     */
    public function hasException();

    /**
     * @return \Exception
     */
    public function getException();

    /**
     * @param MessageInterface $message
     */
    public function addMessage(MessageInterface $message);

    /**
     * @param MessageInterface[] $messages
     */
    public function addMessages(array $messages);
}
