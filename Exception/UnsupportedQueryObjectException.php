<?php

namespace Imatic\Bundle\DataBundle\Exception;

use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class UnsupportedQueryObjectException extends \InvalidArgumentException implements DataExceptionInterface
{
    public function __construct(QueryObjectInterface $queryObject, QueryExecutorInterface $queryExecutor = null)
    {
        $message = sprintf(
            '"%s" is not supported by "%s"',
            get_class($queryObject),
            is_null($queryExecutor) ? 'undefined' : get_class($queryExecutor)
        );
        parent::__construct($message);
    }
}
