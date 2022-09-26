<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\Form\FormInterface;

interface FilterInterface extends \IteratorAggregate, \Countable, \ArrayAccess
{
    public function get(string $index): ?FilterRule;

    public function has(string $index): bool;

    public function hasDefaults(): bool;

    public function getForm(): ?FormInterface;

    public function getTranslationDomain(): ?string;
}
