<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class Message implements MessageInterface
{
    private $type;

    private $text;

    private $parameters;

    public function __construct($type, $text, array $parameters = [])
    {
        $this->type = $type;
        $this->text = $text;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }
}