<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserListWithOrderNumbersQuery implements QueryObjectInterface, SortableQueryObjectInterface
{
    public function build(EntityManager $em): QueryBuilder
    {
        return (new QueryBuilder($em))
            ->from('AppImaticDataBundle:User', 'u')
            ->select('u, COUNT(o.id) order_num')
            ->join('AppImaticDataBundle:Order', 'o', 'WITH', 'u.id = o.user')
            ->groupBy('u.id');
    }

    /**
     * @return array
     */
    public function getSorterMap()
    {
        return [
            'order_num' => 'order_num',
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
