<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserListWithOrderNumbersQuery implements QueryObjectInterface, ResultQueryObjectInterface, SortableQueryObjectInterface
{
    public function build(Connection $connection): QueryBuilder
    {
        return (new UserListQuery())
            ->build($connection)
            ->addSelect('COUNT(uo.id) order_num')
            ->join('u', 'test_user_order', 'uo', 'uo.user_id = u.id')
            ->groupBy('u.id');
    }

    public function getSorterMap(): array
    {
        return [
            'order_num' => 'order_num',
        ];
    }

    public function getDefaultSort(): array
    {
        return [];
    }
}
