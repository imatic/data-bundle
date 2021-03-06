<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ObjectManager;

class EditHandler implements HandlerInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param CommandInterface $command
     *
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command)
    {
        $object = $command->getParameter('data');

        if ($command->hasParameter('class')) {
            $class = $command->getParameter('class');
            if (!($object instanceof $class)) {
                return CommandResult::error('invalid_instance');
            }
        }

        try {
            $this->objectManager->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            return CommandResult::error('constraint_violation');
        }
    }
}
