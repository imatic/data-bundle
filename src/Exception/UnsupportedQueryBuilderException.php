<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Exception;

class UnsupportedQueryBuilderException extends \InvalidArgumentException implements DataExceptionInterface
{
    public function __construct(object $qb)
    {
        $message = \sprintf(
            '"%s" is not supported by any registered display criteria query builder',
            \get_class($qb)
        );

        parent::__construct($message);
    }
}
