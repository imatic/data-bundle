<?php

namespace Imatic\Bundle\DataBundle\Data\Query;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;

interface QueryExecutorInterface
{
    /**
     * Find set of objects.
     *
     * @param QueryObjectInterface $queryObject
     * @param DisplayCriteriaInterface $displayCriteria
     * @return object[]
     */
    public function find(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria);

    /**
     * Find one object.
     *
     * @param QueryObjectInterface $queryObject
     * @return object
     */
    public function findOne(QueryObjectInterface $queryObject);

    /**
     * Execute batch update/delete operation.
     *
     * @param QueryObjectInterface $queryObject
     * @return integer
     */
    public function execute(QueryObjectInterface $queryObject);

    /**
     * Count matched objects.
     *
     * @param QueryObjectInterface $queryObject
     * @return integer Affected rows
     */
    public function count(QueryObjectInterface $queryObject);
}