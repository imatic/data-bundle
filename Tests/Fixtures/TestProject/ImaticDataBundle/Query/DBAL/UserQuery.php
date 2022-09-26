<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\NormalizeResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserQuery implements QueryObjectInterface, NormalizeResultQueryObjectInterface, SingleResultQueryObjectInterface
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function build(Connection $connection): QueryBuilder
    {
        return (new UserListQuery())
            ->build($connection)
            ->andWhere('u.id = :id')
            ->setParameter('id', $this->id)
        ;
    }

    public function getNormalizerMap(): array
    {
        return [
            'birth_date' => Types::DATETIME_MUTABLE,
        ];
    }
}
