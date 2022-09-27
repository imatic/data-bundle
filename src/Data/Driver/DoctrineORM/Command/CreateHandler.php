<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ObjectManager;

class CreateHandler implements HandlerInterface
{
    private ObjectManager $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function handle(CommandInterface $command): CommandResult
    {
        $object = $command->getParameter('data');

        if ($command->hasParameter('class')) {
            $class = $command->getParameter('class');
            if (!($object instanceof $class)) {
                return CommandResult::error('invalid_instance');
            }
        }

        $this->objectManager->persist($object);
        $this->objectManager->flush();

        return CommandResult::success();
    }
}
