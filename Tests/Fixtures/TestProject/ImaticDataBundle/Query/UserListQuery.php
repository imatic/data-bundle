<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class UserListQuery implements QueryObjectInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(ObjectManager $om)
    {
        return (new QueryBuilder($om))
            ->from('AppImaticDataBundle:User', 'u')
            ->select('u')
        ;
    }
}
