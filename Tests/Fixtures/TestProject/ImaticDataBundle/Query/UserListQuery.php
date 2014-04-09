<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;

class UserListQuery implements QueryObjectInterface, FilterableQueryObjectInterface, SortableQueryObjectInterface
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

    /**
     * @return array
     */
    public function getFilterMap()
    {
        return [
            'user_name' => 'u.name',
            'name' => 'u.name',
        ];
    }

    /**
     * @return array
     */
    public function getSorterMap()
    {
        return [
            'user_name' => 'u.name',
            'name' => 'u.name',
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
