<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class UserQuery implements QueryObjectInterface
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
    public function build(ObjectManager $om)
    {
        return (new QueryBuilder($om))
            ->from('AppImaticDataBundle:User', 'u')
            ->select('u')
            ->where('u = :id')
            ->setParameter(':id', $this->id);
    }
}
