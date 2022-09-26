<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Exception;

class DriverNotFoundException extends \InvalidArgumentException implements DataExceptionInterface
{
    public function __construct(string $name)
    {
        $message = \sprintf('Driver "%s" not found', $name);

        parent::__construct($message);
    }
}
