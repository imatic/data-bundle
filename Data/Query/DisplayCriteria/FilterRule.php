<?php
/*
 * This file is part of the ImaticApplicationBundle package.
 *
 * (c) Imatic Software s.r.o. & Stepan Koci <stepan.koci@imatic.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file.
 */

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * String: = != NULL IN + *syntaxe (=), muze byt pole IN
 * Integer: < > = <= >= IN (=) + muze byt pole IN
 * Date/Time: < > = <= >= (=)
 * Boolean: YES NO|NULL (YES)
 * { column: name, value: value, operator: operator }
 *
 * @todo: spatne, prepsat
 */
class FilterRule
{

    protected $column;

    protected $type;

    protected $value;

    protected $operator;

    public function __construct($column, $type, $value = null, $operator = null)
    {
        switch ($type) {

        }
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            throw new \InvalidArgumentException(sprintf('Unknown property %s::%s', __CLASS__, $name));
        }

        return $this->$name;
    }
}
