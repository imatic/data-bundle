<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
interface DisplayCriteriaReader
{
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
}
