<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Data\Driver\DoctrineDBAL;

use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultIteratorFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Filter\User\UserFilter;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL\UserListQuery;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->resultIteratorFactory = self::getContainer()->get(ResultIteratorFactory::class);
    }

    public function testResultIteratorShouldReturnAllResults()
    {
        $resultIterator = $this->resultIteratorFactory->create(new UserListQuery(), [
            'filter_type' => UserFilter::class,
        ]);

        $results = [];
        foreach ($resultIterator as $result) {
            $results[] = $result;
        }

        $this->assertCount(2, $results);
        $this->assertEquals('Adam', $results[0]['name']);
        $this->assertEquals('Eva', $results[1]['name']);
    }

    public function testResultIteratorShouldReturnAllResultsUsingPagination()
    {
        $resultIterator = $this->resultIteratorFactory->create(new UserListQuery(), [
            'filter_type' => UserFilter::class,
            'limit' => 1,
        ]);

        $results = [];
        foreach ($resultIterator as $result) {
            $results[] = $result;
        }

        $this->assertCount(2, $results);
        $this->assertEquals('Adam', $results[0]['name']);
        $this->assertEquals('Eva', $results[1]['name']);
    }

    public function testResultIteratorShouldReturnOneResult()
    {
        $resultIterator = $this->resultIteratorFactory->create(new UserListQuery(), [
            'filter_type' => UserFilter::class,
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
        $this->assertEquals('Adam', $results[0]['name']);
    }
}
