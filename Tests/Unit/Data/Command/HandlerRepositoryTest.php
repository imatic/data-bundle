<?php
namespace Imatic\Bundle\DataBundle\Tests\Unit\Data\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\HandlerRepository;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Command\Handler;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class HandlerRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddHandlerShouldRegisterVariousHandlers()
    {
        $handler1 = new Handler();
        $handler2 = new Handler();

        $handlerRepository = new HandlerRepository();
        $handlerRepository->addHandler('handler1', $handler1, 'AppImaticDataBundle');
        $handlerRepository->addHandler('handler2', $handler2, 'AppImaticDataBundle');

        $command1 = new Command('handler1');
        $command2 = new Command('handler2');

        $this->assertSame($handler1, $handlerRepository->getHandler($command1));
        $this->assertSame($handler2, $handlerRepository->getHandler($command2));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Cannot register 2nd handler with name "handler".
     */
    public function testAddHandlerShouldThrowExceptionIfHandlerWithTheSameNameIsAlreadyRegistered()
    {
        $handlerRepository = new HandlerRepository();
        $handlerRepository->addHandler('handler', new Handler(), 'AppImaticDataBundle');
        $handlerRepository->addHandler('handler', new Handler(), 'AppImaticDataBundle');
    }

    public function testGetHandlers()
    {
        $handler1 = new Handler();
        $handler2 = new Handler();

        $handlerRepository = new HandlerRepository();
        $handlerRepository->addHandler('handler1', $handler1, 'AppImaticDataBundle');
        $handlerRepository->addHandler('handler2', $handler2, 'AppImaticDataBundle');

        $this->assertSame(
            [
                'handler1' => $handler1,
                'handler2' => $handler2,
            ],
            $handlerRepository->getHandlers()
        );
    }

    public function testGetBundleName()
    {
        $handlerRepository = new HandlerRepository();
        $handlerRepository->addHandler('handler1', new Handler(), 'AppImaticDataBundle');

        $this->assertSame('AppImaticDataBundle', $handlerRepository->getBundleName(new Command('handler1')));
    }
}
