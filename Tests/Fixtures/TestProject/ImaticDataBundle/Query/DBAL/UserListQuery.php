<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserListQuery implements QueryObjectInterface, FilterableQueryObjectInterface, SortableQueryObjectInterface
{
    public function build(Connection $connection)
    {
        return $connection->createQueryBuilder()
            ->select('u.id, u.name, u.activated, u.birthDate, u.hairs')
            ->from('user', 'u')
        ;
    }

    /**
     * @return array
     */
    public function getFilterMap()
    {
        return [
            'user_name' => 'u.name',
            'name' => 'u.name',
            'id' => 'u.id',
            'activated' => 'u.activated',
            'birthDate' => 'u.birthDate',
            'hairs' => 'u.hairs',
        ];
    }

    /**
     * @return array
     */
    public function getSorterMap()
    {
        return [
            'user_name' => 'u.name',
            'name' => 'u.name',
        ];
    }

    /**
     * @return array
     */
    public function getDefaultSort()
    {
        return [];
    }
}
