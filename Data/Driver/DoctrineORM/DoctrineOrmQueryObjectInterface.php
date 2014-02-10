<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

interface DoctrineORMQueryObjectInterface extends QueryObjectInterface
{
    /**
     * @param  EntityManager $em
     * @return QueryBuilder
     */
    public function build(EntityManager $em);
}