<?php
namespace Imatic\Bundle\DataBundle\Tests\Data\Driver\DoctrineDBAL\Command;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\SoftDeleteHandler;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class SoftDeleteHandlerTest extends WebTestCase
{
    public function testRemoveSingleRecordWithIdParam()
    {
        // guard
        $this->assertCount(1, $this->findRecords([1]));
        $this->getSoftDeleteHandler()->handle(new Command('handler', [
            'table' => 'test_user_order',
            'id' => 1,
        ]));

        $this->assertCount(0, $this->findRecords([1]));
        $this->assertCount(1, $this->findRecords([3])); // make sure that not everything was removed
    }

    public function testRemoveSingleRecordWithIdsParam()
    {
        // guard
        $this->assertCount(1, $this->findRecords([1]));
        $this->getSoftDeleteHandler()->handle(new Command('handler', [
            'table' => 'test_user_order',
            'ids' => [1],
        ]));

        $this->assertCount(0, $this->findRecords([1]));
        $this->assertCount(1, $this->findRecords([3])); // make sure that not everything was removed
    }

    public function testRemoveMultipleRecords()
    {
        // guard
        $this->assertCount(2, $this->findRecords([1, 2]));
        $this->getSoftDeleteHandler()->handle(new Command('handler', [
            'table' => 'test_user_order',
            'ids' => [1, 2],
        ]));

        $this->assertCount(0, $this->findRecords([1]));
        $this->assertCount(1, $this->findRecords([3])); // make sure that not everything was removed
    }

    public function testRemoveMultipleRecordsWithCombinedParams()
    {
        // guard
        $this->assertCount(2, $this->findRecords([1, 2]));
        $this->getSoftDeleteHandler()->handle(new Command('handler', [
            'table' => 'test_user_order',
            'id' => 1,
            'ids' => [2],
        ]));

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
     * @return SoftDeleteHandler
     */
    private function getSoftDeleteHandler()
    {
        return self::$container->get(SoftDeleteHandler::class);
    }
}
