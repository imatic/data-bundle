<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ResultIteratorFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Exception;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RecordIterator
{
    /**
     * @var QueryExecutorInterface
     */
    protected $queryExecutor;

    /**
     * @var DisplayCriteriaFactory
     */
    protected $displayCriteriaFactory;

    /**
     * @var FilterFactory
     */
    protected $filterFactory;

    /**
     * @var ResultIteratorFactory
     */
    protected $resultIteratorFactory;

    public function __construct(
        QueryExecutorInterface $queryExecutor,
        ResultIteratorFactory $resultIteratorFactory
    ) {
        $this->queryExecutor = $queryExecutor;
        $this->resultIteratorFactory = $resultIteratorFactory;
    }

    public function each(RecordIteratorArgs $recordIteratorArgs)
    {
        $ids = $this->getRecords($recordIteratorArgs->getCommand(), $recordIteratorArgs->getQueryObject());

        return $this->passValues($ids, $recordIteratorArgs->getCallback());
    }

    public function eachIdentifier(RecordIteratorArgs $recordIteratorArgs)
    {
        $ids = $this->getRecordIds($recordIteratorArgs->getCommand(), $recordIteratorArgs->getQueryObject());

        return $this->passValues($ids, $recordIteratorArgs->getCallback());
    }

    protected function passValues($values, callable $callback)
    {
        try {
            $this->queryExecutor->beginTransaction();

            foreach ($values as $value) {
                $result = call_user_func($callback, $value);

                if (!$result->isSuccessful()) {
                    throw new Exception();
                }
            }

            $this->queryExecutor->commit();
        } catch (Exception $e) {
            $this->queryExecutor->rollback();

            $return = CommandResult::error('batch_error');
            if (isset($result)) {
                $return->addMessages($result->getMessages());
            }

            return $return;
        }

        return CommandResult::success('batch_success', ['%count%' => count($values)]);
    }

    protected function getRecordIds(CommandInterface $command, QueryObjectInterface $queryObjectInterface)
    {
        $results = $this->getRecords($command, $queryObjectInterface);

        if (is_array($results)) {
            return $results;
        }

        $ids = [];
        foreach ($results as $result) {
            $ids[] = $result->getId();
        }

        return $ids;
    }

    protected function getRecords(CommandInterface $command, QueryObjectInterface $queryObject)
    {
        $handleAll = $command->getParameter('selectedAll');
        if (!$handleAll) {
            return $command->getParameter('selected');
        }

        $criteria = json_decode($command->getParameter('query'), true);

        return $this->resultIteratorFactory->create($queryObject, $criteria);
    }
}
