<?php

namespace Imatic\Bundle\DataBundle\Tests\Data\Driver\DoctrineDBAL\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\RecordIteratorArgs;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL\UserListQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RecordIteratorTest extends WebTestCase
{
    protected $recordIterator;

    /** @var RequestStack */
    private $requestStack;

    protected function setUp()
    {
        parent::setUp();
        $this->recordIterator = $this->container->get('imatic_data.driver.doctrine_dbal.record_iterator');
        $this->requestStack = $this->container->get('request_stack');
    }

    /**
     * @dataProvider selectedProvider
     */
    public function testIteratorShouldIterateThroughAllRecordIdentifiersGivenFromSelectedOption($selected)
    {
        $command = new Command('unusedHandler', [
            'selectedAll' => false,
            'selected' => $selected,
        ]);

        $ids = [];
        $recordIteratorArgs = new RecordIteratorArgs($command, new UserListQuery(), function ($id) use (&$ids) {
            $ids[] = $id;

            return CommandResult::success();
        });
        $this->recordIterator->eachIdentifier($recordIteratorArgs);

        $this->assertEquals($selected, $ids);
    }

    public function selectedProvider()
    {
        return [
            [[1]],
            [[2]],
            [[1, 2]],
        ];
    }

    public function testIteratorShouldIterateThroughAllRecordIdentifiers()
    {
        $this->requestStack->push(new Request());

        $command = new Command('unusedHandler', [
            'selectedAll' => true,
            'selected' => [],
            'query' => json_encode([
                'filter_type' => 'app_imatic_data.user',
            ]),
        ]);

        $ids = [];
        $recordIteratorArgs = new RecordIteratorArgs($command, new UserListQuery(), function ($id) use (&$ids) {
            $ids[] = $id;

            return CommandResult::success();
        });
        $this->recordIterator->eachIdentifier($recordIteratorArgs);

        sort($ids);
        $this->assertEquals([1, 2], $ids);
    }

    public function testIteratorShouldIterateThroughAllRecords()
    {
        $this->requestStack->push(new Request());

        $command = new Command('unusedHandler', [
            'selectedAll' => true,
            'selected' => [],
            'query' => json_encode([
                'filter_type' => 'app_imatic_data.user',
            ]),
        ]);

        $users = [];
        $recordIteratorArgs = new RecordIteratorArgs($command, new UserListQuery(), function ($user) use (&$users) {
            $users[] = $user;

            return CommandResult::success();
        });
        $this->recordIterator->each($recordIteratorArgs);

        $this->assertCount(2, $users);
        $this->assertEquals(1, $users[0]['id']);
        $this->assertEquals(2, $users[1]['id']);
    }

    public function testIteratorShouldIterateThroughAllRecordsGivenFromSelectedOption()
    {
        $this->requestStack->push(new Request());

        $command = new Command('unusedHandler', [
            'selectedAll' => false,
            'selected' => [1, 2],
            'query' => json_encode([
                'filter_type' => 'app_imatic_data.user',
            ]),
        ]);

        $users = [];
        $recordIteratorArgs = new RecordIteratorArgs($command, new UserListQuery(), function ($user) use (&$users) {
            $users[] = $user;

            return CommandResult::success();
        });
        $this->recordIterator->each($recordIteratorArgs);

        $this->assertCount(2, $users);
        $this->assertEquals(1, $users[0]['id']);
        $this->assertEquals(2, $users[1]['id']);
    }

    public function testIteratorShouldIterateThroughFilteredRecords()
    {
        $this->requestStack->push(new Request());

        $command = new Command('unusedHandler', [
            'selectedAll' => true,
            'selected' => [],
            'query' => json_encode([
                'filter_type' => 'app_imatic_data.user',
                'filter' => [
                    'name' => [
                        'value' => 'Eva',
                        'operator' => FilterOperatorMap::OPERATOR_EQUAL,
                    ],
                ],
            ]),
        ]);

        $users = [];
        $recordIteratorArgs = new RecordIteratorArgs($command, new UserListQuery(), function ($user) use (&$users) {
            $users[] = $user;

            return CommandResult::success();
        });
        $this->recordIterator->each($recordIteratorArgs);

        $this->assertCount(1, $users);
        $this->assertEquals('Eva', $users[0]['name']);
    }
}