<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManager;
use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;

class ObjectManager implements ObjectManagerInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function flush()
    {
        $this->em->flush();
    }

    public function persist($object)
    {
        $this->em->persist($object);
    }

    public function remove($object)
    {
        $this->em->remove($object);
    }
}
