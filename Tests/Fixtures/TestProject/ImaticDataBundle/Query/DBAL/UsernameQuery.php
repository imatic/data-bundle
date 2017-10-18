<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleScalarResultQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class UsernameQuery implements QueryObjectInterface, SingleScalarResultQueryObjectInterface
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function build(Connection $connection)
    {
        return (new UserQuery($this->id))
            ->build($connection)
            ->select('u.name');
    }
}
