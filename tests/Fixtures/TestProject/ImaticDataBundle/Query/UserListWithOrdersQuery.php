<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;

class UserListWithOrdersQuery implements QueryObjectInterface, ResultQueryObjectInterface
{
    public function build(EntityManager $em): QueryBuilder
    {
        $qb = (new QueryBuilder($em))
            ->from(User::class, 'u')
            ->select('u, o')
            ->join('u.orders', 'o');

        return $qb;
    }
}
