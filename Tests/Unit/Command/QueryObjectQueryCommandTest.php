<?php
namespace Imatic\Bundle\DataBundle\Tests\Unit\Command;

use Imatic\Bundle\DataBundle\Command\QueryObjectQueryCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class QueryObjectQueryCommandTest extends \PHPUnit_Framework_TestCase
{
    private $containerMock;

    protected function setUp()
    {
        $this->containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->containerMock
            ->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function ($id) {
                switch ($id) {
                    case 'imatic_data.query_executor':
                        return $this->getMock('Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface');
                }
            }))
        ;
    }

    public function testCommandShouldShouldPrintResultOfQueryObject()
    {
        $queryObjectQueryCommand = new QueryObjectQueryCommand();
        $queryObjectQueryCommand->setContainer($this->containerMock);
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
        $queryObjectQueryCommand = new QueryObjectQueryCommand();
        $queryObjectQueryCommand->setContainer($this->containerMock);
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
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Not enough arguments
     */
    public function testCommandShouldThrowExceptionIfArgumentIsNotPassedToQueryObjectWithRequiredArguments()
    {
        $queryObjectQueryCommand = new QueryObjectQueryCommand();
        $queryObjectQueryCommand->setContainer($this->containerMock);
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
