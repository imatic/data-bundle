<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Utils;

class QueryUtil
{
    public static function generateParameterName(string $prefix = 'param'): string
    {
        static $c = 0;

        return $prefix . ++$c;
    }
}
