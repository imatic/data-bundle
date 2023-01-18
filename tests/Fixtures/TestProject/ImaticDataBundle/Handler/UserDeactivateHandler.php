<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserQuery;

class UserDeactivateHandler implements HandlerInterface
{
    private QueryExecutorInterface $queryExecutor;
    private ObjectManagerInterface $objectManager;

    public function __construct(QueryExecutorInterface $queryExecutor, ObjectManagerInterface $objectManager)
    {
        $this->queryExecutor = $queryExecutor;
        $this->objectManager = $objectManager;
    }

    public function handle(CommandInterface $pathCommand)
    {
        $user = $this->queryExecutor->execute(new UserQuery($pathCommand->getParameter('id')));
        $user->deactivate();

        $this->objectManager->flush();
    }
}
