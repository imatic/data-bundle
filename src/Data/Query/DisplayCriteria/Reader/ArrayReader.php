<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ArrayReader implements DisplayCriteriaReader
{
    /**
     * @var mixed[]
     */
    protected array $attributes = [];

    /**
     * @param mixed[] $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function clearAttribute(string $name, string $component = null, $emptyValue = null): void
    {
        if (null === $emptyValue) {
            unset($this->attributes[$name]);
        } else {
            $this->attributes[$name] = $emptyValue;
        }
    }

    public function readAttribute(string $name, $default = null, string $component = null, bool $persistent = false)
    {
        return \array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }

    public function attributeName(string $name): string
    {
        return $name;
    }
}
