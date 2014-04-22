<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryExecutor;

abstract class AbstractBatchHandler
{
    /**
     * @var QueryExecutor
     */
    protected $queryExecutor;

    /**
     * @param  CommandInterface $command
     * @throws \Exception
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command)
    {
        $ids = $command->getParameter('selected');

        try {
            $this->queryExecutor->beginTransaction();

            foreach ($ids as $id) {
                $result = $this->handleOne($id);

                if (!$result->isSuccessful()) {
                    throw new \Exception();
                }
            }

            $this->queryExecutor->commit();
        } catch (\Exception $e) {
            $this->queryExecutor->rollback();

            $return = CommandResult::error('batch_error');
            if (isset($result)) {
                $return->addMessages($result->getMessages());
            }

            return $return;
        }

        return CommandResult::success('batch_success', ['%count%' => count($ids)]);
    }

    /**
     * @param mixed $id
     * @return CommandResultInterface
     */
    abstract protected function handleOne($id);
}