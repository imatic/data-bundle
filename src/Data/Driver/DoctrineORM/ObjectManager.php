<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManagerInterface;
use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;

class ObjectManager implements ObjectManagerInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }

    public function persist(object $object): void
    {
        $this->em->persist($object);
    }

    public function remove(object $object): void
    {
        $this->em->remove($object);
    }
}
