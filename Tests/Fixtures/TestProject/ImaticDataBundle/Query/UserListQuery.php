<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\DoctrineORMQueryObjectInterface;

class UserListQuery implements DoctrineORMQueryObjectInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(EntityManager $em)
    {
        return (new QueryBuilder($em))
            ->from('AppImaticDataBundle:User', 'u')
            ->select('u');
    }
}
