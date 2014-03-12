<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;

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

    /**
     * {@inheritdoc}
     */
    public function build(EntityManager $em)
    {
        return (new QueryBuilder($em))
            ->from('AppImaticDataBundle:User', 'u')
            ->select('u')
            ->where('u = :id')
            ->setParameter(':id', $this->id);
    }
}
