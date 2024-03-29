<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;

class UserQuery implements QueryObjectInterface, SingleResultQueryObjectInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function build(EntityManager $em): QueryBuilder
    {
        return (new QueryBuilder($em))
            ->from(User::class, 'u')
            ->select('u')
            ->where('u = :id')
            ->setParameter(':id', $this->id);
    }
}
