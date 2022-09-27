<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface SelectableQueryObjectInterface
{
    public function getIdentifierFilterKey(): string;
}
