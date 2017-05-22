<?php

namespace Imatic\Bundle\DataBundle\Tests\Integration\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler\UserDeactivateHandler;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

class CommandHandlerCompilerPassTest extends WebTestCase
{

    public function testHandlersAreRegisteredWithClassNameAndAlias()
    {
        $repository = $this->container->get('imatic_data.command_handler_repository');

        $this->assertTrue(
            $repository->hasHandler('user.deactivate'),
            'Handler is retrievable by alias.'
        );

        $this->assertTrue(
            $repository->hasHandler(UserDeactivateHandler::class),
            'Handler is retrievable by class name.'
        );
    }
}
