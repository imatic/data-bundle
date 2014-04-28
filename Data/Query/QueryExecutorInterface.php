<?php

namespace Imatic\Bundle\DataBundle\Data\Query;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;

interface QueryExecutorInterface
{
    /**
     * Execute query.
     *
     * @param  QueryObjectInterface                     $queryObject
     * @param  DisplayCriteria\DisplayCriteriaInterface $displayCriteria
     * @return mixed
     */
    public function execute(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null);

    /**
     * Count matched objects.
     *
     * @param  QueryObjectInterface                     $queryObject
     * @param  DisplayCriteria\DisplayCriteriaInterface $displayCriteria
     * @return integer                                  Affected rows
     */
    public function count(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null);
}
