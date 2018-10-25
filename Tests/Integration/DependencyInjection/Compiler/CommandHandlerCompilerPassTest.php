<?php
namespace Imatic\Bundle\DataBundle\Tests\Integration\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\HandlerRepositoryInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler\UserDeactivateHandler;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

class CommandHandlerCompilerPassTest extends WebTestCase
{
    public function testHandlersAreRegistered()
    {
        $repository = self::$container->get(HandlerRepositoryInterface::class);
        $handler = $repository->getHandler(new Command(UserDeactivateHandler::class));

        $this->assertEquals(UserDeactivateHandler::class, \get_class($handler));
    }
}
