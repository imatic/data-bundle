<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineDBAL;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query\SoftDeleteQuery;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class SoftDeleteQueryTest extends WebTestCase
{
    public function testRemoveSingleRecord()
    {
        // guard
        $this->assertCount(1, $this->findRecords([1]));
        $this->getQueryExecutor()->execute(new SoftDeleteQuery('test_user_order', 1));

        $this->assertCount(0, $this->findRecords([1]));
        $this->assertCount(1, $this->findRecords([3])); // make sure that not everything was removed
    }

    public function testRemoveMultipleRecords()
    {
        // guard
        $this->assertCount(2, $this->findRecords([1, 2]));
        $this->getQueryExecutor()->execute(new SoftDeleteQuery('test_user_order', [1, 2]));

        $this->assertCount(0, $this->findRecords([1]));
        $this->assertCount(1, $this->findRecords([3])); // make sure that not everything was removed
    }

    private function findRecords($ids)
    {
        return $this->getEntityManager()->getConnection()
            ->createQueryBuilder()
            ->select('r.id')
            ->from('test_user_order', 'r')
            ->where('r.id IN (:ids)')
            ->andWhere('r.deleted_at IS NULL')
            ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
            ->execute()
            ->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return QueryExecutorInterface
     */
    private function getQueryExecutor()
    {
        return self::$container->get(QueryExecutorInterface::class);
    }
}
