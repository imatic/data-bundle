<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class SorterRule
{
    const ASC = 'ASC';

    const DESC = 'DESC';

    const COLUMN_PATTERN = '/^[a-zA-Z0-9]{1,1}[a-zA-Z0-9\.\_]{0,50}[a-zA-Z0-9\_]{1,1}$/';

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $direction;

    /**
     * @param string $column
     * @param string $direction
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($column, $direction = self::ASC)
    {
        if (!preg_match(self::COLUMN_PATTERN, $column)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid column name', $column));
        }

        $this->column = $column;
        $this->direction = (strtoupper($direction) === self::DESC) ? self::DESC : self::ASC;
    }

    /**
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param bool $lowercase
     *
     * @return string
     */
    public function getDirection($lowercase = false)
    {
        $return = $this->direction;
        if ($lowercase) {
            $return = strtolower($return);
        }

        return $return;
    }

    /**
     * @param bool $lowercase
     *
     * @return string
     */
    public function getReverseDirection($lowercase = false)
    {
        $return = $this->direction == self::DESC ? self::ASC : self::DESC;
        if ($lowercase) {
            $return = strtolower($return);
        }

        return $return;
    }

    /**
     * @param string $direction
     *
     * @return bool
     */
    public function isDirection($direction)
    {
        return $this->direction === strtoupper((string) $direction);
    }
}
