<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandResultInterface
{
    /**
     * @return array
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
}
