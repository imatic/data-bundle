<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SelectableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\ResultQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserListQuery implements QueryObjectInterface, ResultQueryObjectInterface, FilterableQueryObjectInterface, SortableQueryObjectInterface, SelectableQueryObjectInterface
{
    public function build(Connection $connection): QueryBuilder
    {
        return $connection->createQueryBuilder()
            ->select('u.id, u.name, u.activated, u.birth_date, u.hairs')
            ->from('test_user', 'u')
        ;
    }

    public function getFilterMap(): array
    {
        return [
            'user_name' => 'u.name',
            'name' => 'u.name',
            'id' => 'u.id',
            'activated' => 'u.activated',
            'birthDate' => 'u.birth_date',
            'hairs' => 'u.hairs',
            'name_hairs' => ['u.name', 'u.hairs'],
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
