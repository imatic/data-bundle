<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ArrayReader implements DisplayCriteriaReader
{
    /**
     * @var array
     */
    protected $attributes = [];

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function clearAttribute($name, $component = null, $emptyValue = null)
    {
        if (null === $emptyValue) {
            unset($this->attributes[$name]);
        } else {
            $this->attributes[$name] = $emptyValue;
        }
    }

    public function readAttribute($name, $default = null, $component = null, $persistent = false)
    {
        return \array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }

    public function attributeName($name)
    {
        return $name;
    }
}
