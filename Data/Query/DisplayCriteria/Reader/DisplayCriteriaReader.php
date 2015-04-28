<?php

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
     * @param  string      $name
     * @param  mixed|null  $default
     * @param  string|null $component
     * @param  bool        $persistent
     *
     * @return mixed
     */
    public function readAttribute($name, $default = null, $component = null, $persistent = true);

    /**
     * @param  string      $name
     * @param  string|null $component
     * @param  mixed       $emptyValue
     */
    public function clearAttribute($name, $component = null, $emptyValue = null);

    /**
     * @param string $name
     *
     * @return string
     */
    public function attributeName($name);
}
