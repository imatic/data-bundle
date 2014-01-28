<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

// UserQuery
class UserQuery implements QueryObjectInterface
{
    /**
     * @var int
     */
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ObjectManager $om)
    {
        return (new QueryBuilder($om))
            ->from('AppUserBundle:User', 'u')
            ->select('u')
            ->where('u = :id')
            ->andWhere('u.deleted != false')
            ->setParameter(':id', $this->id);
    }
}

// create command handler

class UserDeactivateHandler implements HandlerInterface
{
    /**
     * @var Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface
     */
    private $queryExecutor;

    /**
     * @var Imatic\Bundle\DataBundle\Data\ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(QueryExecutorInterface $queryExecutor, ObjectManagerInterface $objectManager)
    {
        $this->queryExecutor = $queryExecutor;
        $this->objectManager = $objectManager;
    }

    public function handle(CommandInterface $pathCommand)
    {
        $user = $this->queryExecutor->findOne(new UserQuery($pathCommand->getParameter('id')));
        $user->deactivate();

        // publish events to update some stats (in transaction) etc..
        // call message queue to send info e-mail (asynchronously)

        $this->objectManager->flush();
    }
}

// create and run command
$command = new Command('user.deactivate', ['id' => 123]);

/** @var CommandResultInterface $result */
$result = $this->get('imatic_data.command_executor')->execute($command);
$result->isSuccessful(); // bool
$result->hasException(); // bool
$result->getException(); // \Exception|null
$result->getMessages(); // [{ type: success|error|..., message: string, params: {key: value} }]
$result->hasMessages(); // bool
