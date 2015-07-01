<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserListWithOrderNumbersQuery implements QueryObjectInterface, SortableQueryObjectInterface
{
    public function build(Connection $connection)
    {
        return (new UserListQuery())
            ->build($connection)
            ->addSelect('COUNT(uo.id) order_num')
            ->join('u', 'test_user_order', 'uo', 'uo.user_id = u.id')
            ->groupBy('u.id')
        ;
    }

    /**
     * @return array
     */
    public function getSorterMap()
    {
        return [
            'order_num' => 'order_num'
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
