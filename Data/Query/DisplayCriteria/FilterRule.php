<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

abstract class FilterRule
{
    const COLUMN_PATTERN = '/^[a-zA-Z0-9]{1,1}[a-zA-Z0-9\_]{0,50}[a-zA-Z0-9\_]{1,1}$/';

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
     * @param string $column
     * @param mixed $value
     * @param string|null $operator
     * @throws \InvalidArgumentException
     */
    public function __construct($column, $value, $operator = null)
    {
        if (!preg_match(self::COLUMN_PATTERN, $column)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid column name', $column));
        }
        if (!$this->validateValue($value)) {
            throw new \InvalidArgumentException(sprintf('Invalid filter value "%s" for "%s"', var_export($value, true), $column));
        }

        $operator = strtolower($operator);
        $operators = static::getOperators();
        if (!in_array($operator, $operators)) {
            $operator = reset($operators);
        }
        $this->column = $column;
        $this->value = $value;
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    abstract protected function validateValue($value);

    public static function getOperators() {}

    /**
     * @return array
     */
    private function getOperatorMap()
    {
        return [
            'string' => ['equal', 'not-equal', 'contains', 'not-contains', 'null', 'not-null'], // text
            'number' => ['equal', 'not-equal', 'greater', 'lesser', 'greater-equal', 'lesser-equal', 'null', 'not-null'], // number
            'bool' => ['null'], // values yes, no, yes-no, null
            'date' => ['equal', 'not-equal', 'greater', 'lesser', 'greater-equal', 'lesser-equal', 'null', 'not-null'], // date
            'date_range' => ['between', 'not-between'], // date_from, date_to
        ];
    }
}
