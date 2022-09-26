<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleScalarResultQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UsernameQuery implements QueryObjectInterface, SingleScalarResultQueryObjectInterface
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function build(Connection $connection): QueryBuilder
    {
        return (new UserQuery($this->id))
            ->build($connection)
            ->select('u.name');
    }
}
