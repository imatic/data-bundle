<?php
namespace Imatic\Bundle\DataBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class ArrayToStringTransformer implements DataTransformerInterface
{
    public function reverseTransform($value)
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (\is_array($value)) {
            return $value;
        }

        return \array_map('trim', \explode(',', $value));
    }

    public function transform($value)
    {
        if (!$value) {
            return '';
        }

        return \implode(',', \array_map('trim', $value));
    }
}
