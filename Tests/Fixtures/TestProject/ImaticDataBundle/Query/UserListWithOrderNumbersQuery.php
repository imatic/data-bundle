<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\DoctrineORMQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserListWithOrderNumbersQuery implements DoctrineORMQueryObjectInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(EntityManager $em)
    {
        return (new QueryBuilder($em))
            ->from('AppImaticDataBundle:User', 'u')
            ->select('u, COUNT(o.id) order_num')
            ->join('AppImaticDataBundle:Order', 'o', 'WITH', 'u.id = o.user')
            ->groupBy('u.id');
    }
}
