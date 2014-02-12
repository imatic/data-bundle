<?php
namespace Imatic\Bundle\DataBundle\Tests\Data\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerRepository;

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
        $handlerRepository->addHandler('handler1', $handler1);
        $handlerRepository->addHandler('handler2', $handler2);

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
        $handlerRepository->addHandler('handler', new Handler());
        $handlerRepository->addHandler('handler', new Handler());
    }
}

class Handler implements HandlerInterface
{
    public function handle(CommandInterface $command)
    {
    }
}
