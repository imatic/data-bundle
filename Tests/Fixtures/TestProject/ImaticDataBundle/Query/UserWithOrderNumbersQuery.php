<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserWithOrderNumbersQuery implements QueryObjectInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(ObjectManager $om)
    {
        return (new QueryBuilder($om))
            ->from('AppImaticDataBundle:User', 'u')
            ->select('u, COUNT(o.id) order_num')
            ->join('AppImaticDataBundle:Order','o', 'WITH', 'u.id = o.user')
            ->groupBy('u.id')
        ;
    }
}
