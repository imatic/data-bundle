<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserQuery;

class UserDeactivateHandler implements HandlerInterface
{
    /**
     * @var QueryExecutorInterface
     */
    private $queryExecutor;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param QueryExecutorInterface $queryExecutor
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(QueryExecutorInterface $queryExecutor, ObjectManagerInterface $objectManager)
    {
        $this->queryExecutor = $queryExecutor;
        $this->objectManager = $objectManager;
    }

    /**
     * @param CommandInterface $pathCommand
     *
     * @return \Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $pathCommand)
    {
        $user = $this->queryExecutor->execute(new UserQuery($pathCommand->getParameter('id')));
        $user->deactivate();

        $this->objectManager->flush();
    }
}
