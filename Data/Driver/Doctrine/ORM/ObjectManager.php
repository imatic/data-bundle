<?php

namespace Imatic\Bundle\DataBundle\Driver\Doctrine\ORM;

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

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function persist($object)
    {
        $this->em->persist($object);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {
        $this->em->remove($object);
    }
}
