<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;

interface QueryExecutorInterface
{
    /**
     * Execute query.
     *
     * @return mixed
     */
    public function execute(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null);

    /**
     * Count matched objects.
     *
     * @return int Affected rows
     */
    public function count(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null): int;

    /**
     * Execute query and count the matched objects.
     *
     * This is done using a single query if the implementation supports it.
     *
     * @return array<mixed,int>
     */
    public function executeAndCount(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null): array;
}
