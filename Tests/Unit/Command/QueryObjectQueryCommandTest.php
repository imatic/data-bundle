<?php
namespace Imatic\Bundle\DataBundle\Tests\Unit\Command;

use Imatic\Bundle\DataBundle\Command\QueryObjectQueryCommand;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class QueryObjectQueryCommandTest extends TestCase
{
    private $queryExecutorMock;

    protected function setUp()
    {
        $this->queryExecutorMock = $this->createMock(QueryExecutorInterface::class);
    }

    public function testCommandShouldShouldPrintResultOfQueryObject()
    {
        $queryObjectQueryCommand = new QueryObjectQueryCommand($this->queryExecutorMock);

        $application = new Application();
        $application->add($queryObjectQueryCommand);

        $command = $application->find('imatic:data:query-object-query');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'class' => 'Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListQuery',
        ]);

        $this->assertRegExp('/^NULL/', $commandTester->getDisplay());
    }

    public function testCommandShouldShouldPrintResultOfQueryObjectWithRequiredArguments()
    {
        $queryObjectQueryCommand = new QueryObjectQueryCommand($this->queryExecutorMock);

        $application = new Application();
        $application->add($queryObjectQueryCommand);

        $command = $application->find('imatic:data:query-object-query');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'class' => 'Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserQuery',
            '--args' => [1],
        ]);

        $this->assertRegExp('/^NULL/', $commandTester->getDisplay());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Not enough arguments
     */
    public function testCommandShouldThrowExceptionIfArgumentIsNotPassedToQueryObjectWithRequiredArguments()
    {
        $queryObjectQueryCommand = new QueryObjectQueryCommand($this->queryExecutorMock);

        $application = new Application();
        $application->add($queryObjectQueryCommand);

        $command = $application->find('imatic:data:query-object-query');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'class' => 'Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserQuery',
        ]);
    }
}
