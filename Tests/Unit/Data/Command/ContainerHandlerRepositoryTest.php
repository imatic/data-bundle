<?php

namespace Imatic\Bundle\DataBundle\Tests\Unit\Data\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\ContainerHandlerRepository;
use Imatic\Bundle\DataBundle\Data\Command\HandlerRepository;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Command\Handler;
use Symfony\Component\DependencyInjection\Container;

class ContainerContainerHandlerRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddHandlerShouldRegisterVariousHandlers()
    {
        $handler1 = new Handler();
        $handler2 = new Handler();
        $handler3 = new Handler();

        $container = new Container();
        $container->set('app.handler1', $handler1);
        $container->set('app.handler2', $handler2);

        $handlerRepository = new ContainerHandlerRepository($container, new HandlerRepository());
        $handlerRepository->addLazyHandler('handler1', 'app.handler1', 'AppImaticDataBundle');
        $handlerRepository->addLazyHandler('handler2', 'app.handler2', 'AppImaticDataBundle');
        $handlerRepository->addHandler('handler3', $handler3, 'AppImaticDataBundle');

        $command1 = new Command('handler1');
        $command2 = new Command('handler2');
        $command3 = new Command('handler3');

        $this->assertSame($handler1, $handlerRepository->getHandler($command1));
        $this->assertSame($handler2, $handlerRepository->getHandler($command2));
        $this->assertSame($handler3, $handlerRepository->getHandler($command3));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Cannot register 2nd handler with name "handler".
     */
    public function testAddLazyHandlerShouldThrowExceptionIfLazyHandlerWithTheSameNameIsAlreadyRegistered()
    {
        $handlerRepository = new ContainerHandlerRepository(new Container(), new HandlerRepository());
        $handlerRepository->addLazyHandler('handler', 'app.handler1', 'AppImaticDataBundle');
        $handlerRepository->addLazyHandler('handler', 'app.handler2', 'AppImaticDataBundle');
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Cannot register 2nd handler with name "handler".
     */
    public function testAddLazyHandlerShouldThrowExceptionIfHandlerWithTheSameNameIsAlreadyRegistered()
    {
        $handlerRepository = new ContainerHandlerRepository(new Container(), new HandlerRepository());
        $handlerRepository->addHandler('handler', new Handler(), 'AppImaticDataBundle');
        $handlerRepository->addLazyHandler('handler', 'app.handler2', 'AppImaticDataBundle');
    }

    public function testGetHandlers()
    {
        $handler1 = new Handler();
        $handler2 = new Handler();
        $handler3 = new Handler();

        $container = new Container();
        $container->set('app.handler1', $handler1);
        $container->set('app.handler2', $handler2);

        $handlerRepository = new ContainerHandlerRepository($container, new HandlerRepository());
        $handlerRepository->addLazyHandler('handler1', 'app.handler1', 'AppImaticDataBundle');
        $handlerRepository->addLazyHandler('handler2', 'app.handler2', 'AppImaticDataBundle');
        $handlerRepository->addHandler('handler3', $handler3, 'AppImaticDataBundle');

        $this->assertSame(
            [
                'handler3' => $handler3,
                'handler1' => $handler1,
                'handler2' => $handler2,
            ],
            $handlerRepository->getHandlers()
        );
    }

    public function testGetBundleName()
    {
        $handlerRepository = new ContainerHandlerRepository(new Container(), new HandlerRepository());
        $handlerRepository->addLazyHandler('handler1', 'app.handler1', 'AppImaticDataBundle');
        $handlerRepository->addHandler('handler2', new Handler(), 'AppImaticDataBundle');

        $this->assertSame('AppImaticDataBundle', $handlerRepository->getBundleName(new Command('handler1')));
        $this->assertSame('AppImaticDataBundle', $handlerRepository->getBundleName(new Command('handler2')));
    }
}
