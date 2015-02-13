<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL;

use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query\RecordIdQuery;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RecordIdQueryTest extends WebTestCase
{
    public function testRecordIdQueryShouldReturnEmptyResultIfRecordDoesNotExists()
    {
        $recordQuery = new RecordIdQuery('user', [
            'name' => 'non-existent',
        ]);

        $result = $this->getQueryExecutor()->execute($recordQuery);
        $this->assertEmpty($result);
    }

    public function testRecordIdQueryShouldReturnIdOfTheRecordIfRecordExists()
    {
        $recordQuery = new RecordIdQuery('user', [
            'name' => 'Adam',
        ]);

        $result = $this->getQueryExecutor()->execute($recordQuery);
        $this->assertEquals(['id' => 1], $result);
    }

    /**
     * @return QueryExecutorInterface
     */
    private function getQueryExecutor()
    {
        return $this->container->get('imatic_data.query_executor');
    }
}
