<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class Message implements MessageInterface
{
    private $type;

    private $prefix;

    private $text;

    private $parameters;

    private $translationDomain;

    public function __construct($type, $text, array $parameters = [], $translationDomain = null)
    {
        $this->type = $type;
        $this->text = $text;
        $this->parameters = $parameters;
        $this->translationDomain = $translationDomain;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getMessage()
    {
        $prefix = $this->prefix ? $this->prefix . '.' : '';

        return $prefix . $this->text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}