<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface FilterableQueryObjectInterface
{
    /**
     * @return array<string,string> Where key is name of the filter and value is field to filter by.
     */
    public function getFilterMap(): array;
}
