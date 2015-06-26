<?php

namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;

class UserListWithOrdersQuery implements QueryObjectInterface
{
    public function build(EntityManager $em)
    {
        $qb = (new QueryBuilder($em))
            ->from('AppImaticDataBundle:User', 'u')
            ->select('u, o')
            ->join('u.orders', 'o');

        return $qb;
    }
}
