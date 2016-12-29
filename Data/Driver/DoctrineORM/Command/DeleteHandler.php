<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ObjectManager;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class DeleteHandler implements HandlerInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var QueryExecutorInterface
     */
    private $queryExecutor;

    public function __construct(ObjectManager $objectManager, QueryExecutorInterface $queryExecutor)
    {
        $this->objectManager = $objectManager;
        $this->queryExecutor = $queryExecutor;
    }

    /**
     * @param CommandInterface $command
     *
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command)
    {
        $class = $command->getParameter('class');
        $object = $command->hasParameter('object') ? $command->getParameter('object') : null;

        // if no object has been given, try loading it using a query object
        if (!$object) {
            $object = $this->queryExecutor->execute($command->getParameter('query_object'));
        }

        // try removing the object if it is valid
        if ($object instanceof $class) {
            try {
                $this->objectManager->remove($object);
                $this->objectManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                return CommandResult::error('constraint_violation');
            }
        }
    }
}
