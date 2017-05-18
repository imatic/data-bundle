<?php
namespace Imatic\Bundle\DataBundle\Utils;

class QueryUtil
{
    public static function generateParameterName($prefix = 'param')
    {
        static $c = 0;

        return $prefix . ++$c;
    }
}
