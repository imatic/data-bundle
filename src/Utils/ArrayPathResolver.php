<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Utils;

/**
 * Array path resolver.
 *
 * Code ported from pre-3.0 Symfony\Component\HttpFoundation\ParameterBag
 *
 * @author Pavel Batecko <pavel.batecko@imatic.cz>
 */
class ArrayPathResolver
{
    /** @var array */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $path
     * @param mixed  $default
     *
     * @return mixed
     */
    public function resolve($path, $default = null)
    {
        if (false === ($pos = \strpos($path, '['))) {
            return \array_key_exists($path, $this->data) ? $this->data[$path] : $default;
        }

        $root = \substr($path, 0, $pos);
        if (!\array_key_exists($root, $this->data)) {
            return $default;
        }

        $value = $this->data[$root];
        $currentKey = null;
        for ($i = $pos, $c = \strlen($path); $i < $c; ++$i) {
            $char = $path[$i];

            if ('[' === $char) {
                if (null !== $currentKey) {
                    throw new \InvalidArgumentException(\sprintf('Malformed path. Unexpected "[" at position %d.', $i));
                }

                $currentKey = '';
            } elseif (']' === $char) {
                if (null === $currentKey) {
                    throw new \InvalidArgumentException(\sprintf('Malformed path. Unexpected "]" at position %d.', $i));
                }

                if (!\is_array($value) || !\array_key_exists($currentKey, $value)) {
                    return $default;
                }

                $value = $value[$currentKey];
                $currentKey = null;
            } else {
                if (null === $currentKey) {
                    throw new \InvalidArgumentException(\sprintf('Malformed path. Unexpected "%s" at position %d.', $char, $i));
                }

                $currentKey .= $char;
            }
        }

        if (null !== $currentKey) {
            throw new \InvalidArgumentException(\sprintf('Malformed path. Path must end with "]".'));
        }

        return $value;
    }
}
