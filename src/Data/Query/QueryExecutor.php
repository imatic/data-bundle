<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query;

use Imatic\Bundle\DataBundle\Data\Driver\DriverRepositoryInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;

class QueryExecutor implements QueryExecutorInterface
{
    private DriverRepositoryInterface$driverRepository;

    public function __construct(DriverRepositoryInterface $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function execute(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        return $this->driverRepository->getQueryExecutorFor($queryObject)->execute($queryObject, $displayCriteria);
    }

    public function count(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null): int
    {
        return $this->driverRepository->getQueryExecutorFor($queryObject)->count($queryObject, $displayCriteria);
    }

    public function executeAndCount(QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null): array
    {
        return $this->driverRepository->getQueryExecutorFor($queryObject)->executeAndCount($queryObject, $displayCriteria);
    }
}
