<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserListWithOrderNumbersQuery implements QueryObjectInterface, ResultQueryObjectInterface, SortableQueryObjectInterface
{
    public function build(EntityManager $em): QueryBuilder
    {
        return (new QueryBuilder($em))
            ->from(User::class, 'u')
            ->select('u, COUNT(o.id) order_num')
            ->join('AppImaticDataBundle:Order', 'o', 'WITH', 'u.id = o.user')
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
