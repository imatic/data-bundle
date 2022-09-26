<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class SorterRule
{
    const ASC = 'ASC';
    const DESC = 'DESC';
    const COLUMN_PATTERN = '/^[a-zA-Z0-9]{1,1}[a-zA-Z0-9\.\_]{0,50}[a-zA-Z0-9\_]{1,1}$/';

    protected string $column;
    protected string $direction;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(string $column, string $direction = self::ASC)
    {
        if (!\preg_match(self::COLUMN_PATTERN, $column)) {
            throw new \InvalidArgumentException(\sprintf('"%s" is not valid column name', $column));
        }

        $this->column = $column;
        $this->direction = (\strtoupper($direction) === self::DESC) ? self::DESC : self::ASC;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getDirection(bool $lowercase = false): string
    {
        $return = $this->direction;
        if ($lowercase) {
            $return = \strtolower($return);
        }

        return $return;
    }

    public function getReverseDirection(bool $lowercase = false): string
    {
        $return = $this->direction === self::DESC ? self::ASC : self::DESC;
        if ($lowercase) {
            $return = \strtolower($return);
        }

        return $return;
    }

    public function isDirection(string $direction): bool
    {
        return $this->direction === \strtoupper($direction);
    }
}
