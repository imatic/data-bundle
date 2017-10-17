<?php

namespace Imatic\Bundle\DataBundle\Tests\Data\Driver\DoctrineORM;

use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ResultIteratorFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ResultIteratorTest extends WebTestCase
{
    /**
     * @var ResultIteratorFactory
     */
    protected $resultIteratorFactory;

    protected function setUp()
    {
        parent::setUp();
        $this->resultIteratorFactory = $this->container->get(ResultIteratorFactory::class);
    }

    public function testResultIteratorShouldReturnAllResults()
    {
        $resultIterator = $this->resultIteratorFactory->create(new UserListQuery(), [
            'filter_type' => 'app_imatic_data.user',
        ]);

        $results = [];
        foreach ($resultIterator as $result) {
            $results[] = $result;
        }

        $this->assertCount(2, $results);
        $this->assertEquals('Adam', $results[0]->getName());
        $this->assertEquals('Eva', $results[1]->getName());
    }

    public function testResultIteratorShouldReturnAllResultsUsingPagination()
    {
        $resultIterator = $this->resultIteratorFactory->create(new UserListQuery(), [
            'filter_type' => 'app_imatic_data.user',
            'limit' => 1,
        ]);

        $results = [];
        foreach ($resultIterator as $result) {
            $results[] = $result;
        }

        $this->assertCount(2, $results);
        $this->assertEquals('Adam', $results[0]->getName());
        $this->assertEquals('Eva', $results[1]->getName());
    }

    public function testResultIteratorShouldReturnOneResult()
    {
        $resultIterator = $this->resultIteratorFactory->create(new UserListQuery(), [
            'filter_type' => 'app_imatic_data.user',
            'filter' => [
                'name' => [
                    'value' => 'Adam',
                    'operator' => FilterOperatorMap::OPERATOR_EQUAL,
                ],
            ],
        ]);

        $results = [];
        foreach ($resultIterator as $result) {
            $results[] = $result;
        }

        $this->assertCount(1, $results);
        $this->assertEquals('Adam', $results[0]->getName());
    }
}
