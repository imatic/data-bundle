<?php

namespace Imatic\Bundle\DataBundle\Tests\Integration\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

class CommandHandlerCompilerPassTest extends WebTestCase
{
    public function testHandlersAreRegisteredWithServiceIdAndAlias()
    {
        $repository = $this->container->get('imatic_data.command_handler_repository');

        $this->assertTrue(
            $repository->hasHandler('user.deactivate'),
            'Handler is retrievable by alias.'
        );

        $this->assertTrue(
            $repository->hasHandler('app_imatic_data.handler.user_deactivate_handler'),
            'Handler is retrievable by class name.'
        );
    }
}
