<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface SortableQueryObjectInterface
{
    /**
     * @return array<string,string> Where key is name of the sorter and value is field to sort by.
     */
    public function getSorterMap(): array;

    /**
     * @return array<string,string> Where key is name of the sorter and value is field to sort by.
     */
    public function getDefaultSort(): array;
}
