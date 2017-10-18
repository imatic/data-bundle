<?php
namespace Imatic\Bundle\DataBundle\Tests\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\RecordIterator;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\RecordIteratorArgs;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListQuery;
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
        $this->recordIterator = $this->container->get(RecordIterator::class);
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
            'query' => \json_encode([
                'filter' => null,
                'filter_type' => 'app_imatic_data.user',
            ]),
        ]);

        $ids = [];
        $recordIteratorArgs = new RecordIteratorArgs($command, new UserListQuery(), function ($id) use (&$ids) {
            $ids[] = $id;

            return CommandResult::success();
        });
        $this->assertValidRecordIteratorResult($this->recordIterator->eachIdentifier($recordIteratorArgs));

        $this->assertEquals($selected, $ids);
    }

    /**
     * @dataProvider selectedProvider
     */
    public function testIteratorShouldIterateThroughAllRecordsGivenFromSelectedOption($selected)
    {
        $command = new Command('unusedHandler', [
            'selectedAll' => false,
            'selected' => $selected,
            'query' => \json_encode([
                'filter' => null,
                'filter_type' => 'app_imatic_data.user',
            ]),
        ]);

        $users = [];
        $recordIteratorArgs = new RecordIteratorArgs($command, new UserListQuery(), function (User $user) use (&$users) {
            $users[] = $user;

            return CommandResult::success();
        });
        $this->assertValidRecordIteratorResult($this->recordIterator->each($recordIteratorArgs));

        $ids = \array_map(
            function (User $user) {
                return $user->getId();
            },
            $users
        );

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
            'query' => \json_encode([
                'filter_type' => 'app_imatic_data.user',
            ]),
        ]);

        $ids = [];
        $recordIteratorArgs = new RecordIteratorArgs($command, new UserListQuery(), function ($id) use (&$ids) {
            $ids[] = $id;

            return CommandResult::success();
        });
        $this->assertValidRecordIteratorResult($this->recordIterator->eachIdentifier($recordIteratorArgs));

        \sort($ids);
        $this->assertEquals([1, 2], $ids);
    }

    public function testIteratorShouldIterateThroughAllRecords()
    {
        $this->requestStack->push(new Request());

        $command = new Command('unusedHandler', [
            'selectedAll' => true,
            'selected' => [],
            'query' => \json_encode([
                'filter_type' => 'app_imatic_data.user',
            ]),
        ]);

        $users = [];
        $recordIteratorArgs = new RecordIteratorArgs($command, new UserListQuery(), function ($user) use (&$users) {
            $users[] = $user;

            return CommandResult::success();
        });
        $this->assertValidRecordIteratorResult($this->recordIterator->each($recordIteratorArgs));

        $this->assertCount(2, $users);
        $this->assertEquals(1, $users[0]->getId());
        $this->assertEquals(2, $users[1]->getId());
    }

    public function testIteratorShouldIterateThroughFilteredRecords()
    {
        $this->requestStack->push(new Request());

        $command = new Command('unusedHandler', [
            'selectedAll' => true,
            'selected' => [],
            'query' => \json_encode([
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
        $this->assertValidRecordIteratorResult($this->recordIterator->each($recordIteratorArgs));

        $this->assertCount(1, $users);
        $this->assertEquals('Eva', $users[0]->getName());
    }

    private function assertValidRecordIteratorResult(CommandResult $result)
    {
        if ($result->getException()) {
            throw $result->getException();
        }

        $this->assertTrue($result->isSuccessful());
    }
}
