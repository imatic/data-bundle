<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Exception;

class ParameterNotFoundException extends \InvalidArgumentException implements DataExceptionInterface
{
    public function __construct(string $parameterName)
    {
        $message = \sprintf('Parameter "%s" not found', $parameterName);

        parent::__construct($message);
    }
}
