<?php

namespace Imatic\Bundle\DataBundle\Form\Extension;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface as DoctrineORMQueryObjectInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;

class EntityTypeQueryObjectLoader implements EntityLoaderInterface
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var QueryExecutorInterface */
    private $queryExecutor;
    /** @var QueryObjectInterface */
    private $queryObject;

    public function __construct(
        EntityManagerInterface $em,
        QueryExecutorInterface $queryExecutor,
        QueryObjectInterface $queryObject
    ) {
        $this->em = $em;
        $this->queryExecutor = $queryExecutor;
        $this->queryObject = $queryObject;
    }

    public function getEntities()
    {
        return $this->queryExecutor->execute($this->queryObject);
    }

    public function getEntitiesByIds($identifier, array $values)
    {
        if (!$this->queryObject instanceof DoctrineORMQueryObjectInterface) {
            throw new \RuntimeException('Unsupported query object');
        }

        $qb = $this->queryObject->build($this->em);
        $alias = current($qb->getRootAliases());
        $parameter = 'EntityTypeQueryObjectLoader_getEntitiesByIds_' . $identifier;
        $where = $qb->expr()->in($alias . '.' . $identifier, ':' . $parameter);

        // guess type
        $entity = current($qb->getRootEntities());
        $metadata = $this->em->getClassMetadata($entity);
        if (in_array($metadata->getTypeOfField($identifier), array('integer', 'bigint', 'smallint'))) {
            $parameterType = Connection::PARAM_INT_ARRAY;
        } else {
            $parameterType = Connection::PARAM_STR_ARRAY;
        }

        return $qb
            ->andWhere($where)
            ->getQuery()
            ->setParameter($parameter, $values, $parameterType)
            ->getResult()
        ;
    }
}
