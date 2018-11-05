<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UserQuery implements QueryObjectInterface, SingleResultQueryObjectInterface
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function build(Connection $connection)
    {
        return (new UserListQuery())
            ->build($connection)
            ->andWhere('u.id = :id')
            ->setParameter('id', $this->id);
    }
}
