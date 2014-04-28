<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ObjectManager;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;

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
     * @param  CommandInterface                 $command
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command)
    {
        $object = $command->getParameter('object');
        $class = $command->getParameter('class');

        if (!($object instanceof $class)) {
            $object = $this->queryExecutor->execute($command->getParameter('query_object'));
        }

        $this->objectManager->remove($object);
        $this->objectManager->flush();
    }
}
