<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SelectableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;

class UserListQuery implements QueryObjectInterface, ResultQueryObjectInterface, FilterableQueryObjectInterface, SortableQueryObjectInterface, SelectableQueryObjectInterface
{
    public function build(EntityManager $em): QueryBuilder
    {
        return (new QueryBuilder($em))
            ->from(User::class, 'u')
            ->select('u');
    }

    public function getFilterMap(): array
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

    public function getSorterMap(): array
    {
        return [
            'user_name' => 'u.name',
            'name' => 'u.name',
        ];
    }

    public function getDefaultSort(): array
    {
        return [];
    }

    public function getIdentifierFilterKey(): string
    {
        return 'id';
    }
}
