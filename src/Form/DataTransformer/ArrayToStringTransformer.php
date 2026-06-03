<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/** @implements DataTransformerInterface<mixed, mixed> */
class ArrayToStringTransformer implements DataTransformerInterface
{
    public function reverseTransform(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (\is_array($value)) {
            return $value;
        }

        return \array_map('trim', \explode(',', (string) $value));
    }

    public function transform(mixed $value): mixed
    {
        if (!$value) {
            return '';
        }

        return \implode(',', \array_map('trim', $value));
    }
}
