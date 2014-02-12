<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface MessageInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @return string
     */
    public function getText();
}