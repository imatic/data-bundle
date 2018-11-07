<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SelectableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;

class UserListQuery implements QueryObjectInterface, FilterableQueryObjectInterface, SortableQueryObjectInterface, SelectableQueryObjectInterface
{
    public function build(EntityManager $em): QueryBuilder
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
            'id' => 'u.id',
            'activated' => 'u.activated',
            'birthDate' => 'u.birthDate',
            'hairs' => 'u.hairs',
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

    public function getIdentifierFilterKey()
    {
        return 'id';
    }
}
