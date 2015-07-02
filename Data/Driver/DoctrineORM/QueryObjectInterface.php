<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as BaseQueryObjectInterface;

interface QueryObjectInterface extends BaseQueryObjectInterface
{
    /**
     * @param EntityManager $em
     * @return QueryBuilder
     */
    public function build(EntityManager $em);
}
