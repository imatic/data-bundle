<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Unit\Command;

use Imatic\Bundle\DataBundle\Command\QueryObjectQueryCommand;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class QueryObjectQueryCommandTest extends TestCase
{
    private $queryExecutorMock;

    protected function setUp(): void
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

        $this->assertMatchesRegularExpression('/^NULL/', $commandTester->getDisplay());
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

        $this->assertMatchesRegularExpression('/^NULL/', $commandTester->getDisplay());
    }

    public function testCommandShouldThrowExceptionIfArgumentIsNotPassedToQueryObjectWithRequiredArguments()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Not enough arguments');

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
