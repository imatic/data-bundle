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
 */
class FilterRule
{
    const CONDITION_AND = 'AND';
    const CONDITION_OR = 'OR';

    const COLUMN_PATTERN = '/^[a-zA-Z0-9]{1,1}[a-zA-Z0-9\.\_]{0,50}[a-zA-Z0-9\_]{1,1}$/';

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var string
     */
    protected $condition;

    /**
     * @var bool
     */
    protected $aggregated;

    /**
     * @param string $column
     * @param string $value
     * @param string $operator
     * @param string $condition
     */
    public function __construct($column, $value, $operator, $condition = self::CONDITION_AND, $aggregated = false)
    {
        if (!preg_match(self::COLUMN_PATTERN, $column)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid column name', $column));
        }

        $this->column = $column;
        $this->value = $value;
        $this->operator = $operator;
        $this->condition = $condition;
        $this->aggregated = $aggregated;
    }

    /**
     * @var string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @var string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @var string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @var string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return bool
     */
    public function isAggregated()
    {
        return $this->aggregated;
    }
}
