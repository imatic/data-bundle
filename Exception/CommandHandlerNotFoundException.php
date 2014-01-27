<?php

namespace Imatic\Bundle\DataBundle\Exception;

class CommandHandlerNotFoundException extends \InvalidArgumentException implements DataExceptionInterface
{
    public function __construct($commandHandlerName)
    {
        $message = sprintf('Command handler "%s" not found', $commandHandlerName);
        parent::__construct($message);
    }
}