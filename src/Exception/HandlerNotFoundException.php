<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Exception;

class HandlerNotFoundException extends \InvalidArgumentException implements DataExceptionInterface
{
    public function __construct(string $handlerName)
    {
        $message = \sprintf('Command handler "%s" not found', $handlerName);
        parent::__construct($message);
    }
}
