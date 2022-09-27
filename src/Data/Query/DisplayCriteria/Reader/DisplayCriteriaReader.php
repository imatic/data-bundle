<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
interface DisplayCriteriaReader
{
    const FILTER = 'filter';
    const SORTER = 'sorter';
    const PAGE = 'page';
    const LIMIT = 'limit';

    /**
     * @param mixed|null  $default
     *
     * @return mixed
     */
    public function readAttribute(string $name, $default = null, string $component = null, bool $persistent = false);

    /**
     * @param mixed $emptyValue
     */
    public function clearAttribute(string $name, string $component = null, $emptyValue = null): void;

    public function attributeName(string $name): string;
}
