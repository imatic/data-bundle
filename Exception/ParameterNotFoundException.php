<?php
namespace Imatic\Bundle\DataBundle\Exception;

class ParameterNotFoundException extends \InvalidArgumentException implements DataExceptionInterface
{
    public function __construct($parameterName)
    {
        $message = \sprintf('Parameter "%s" not found', $parameterName);
        parent::__construct($message);
    }
}
