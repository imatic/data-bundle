<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Exception;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultIteratorFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;

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

            $return = CommandResult::error('batch_error', [], $e);
            if (isset($result)) {
                $return->addMessages($result->getMessages());
            }

            return $return;
        }

        return CommandResult::success('batch_success', ['%count%' => count($values)]);
    }

    protected function getRecordIds(CommandInterface $command, QueryObjectInterface $queryObjectInterface)
    {
        $handleAll = $command->getParameter('selectedAll');
        if (!$handleAll) {
            return $command->getParameter('selected');
        }

        $results = $this->getRecords($command, $queryObjectInterface);

        $ids = [];
        foreach ($results as $result) {
            $ids[] = $result['id'];
        }

        return $ids;
    }

    protected function getRecords(CommandInterface $command, QueryObjectInterface $queryObject)
    {
        $handleAll = $command->getParameter('selectedAll');

        $queryCriteria = json_decode($command->getParameter('query'), true);

        $criteria = [];
        if (!$handleAll) {
            $ids = $command->getParameter('selected');
            $criteria = [
                'filter_type' => $queryCriteria['filter_type'],
                'filter' => [
                    'id' => [
                        'value' => $ids,
                        'operator' => FilterOperatorMap::OPERATOR_IN,
                    ],
                ]
            ];
        } else {
           $criteria = $queryCriteria;
        }

        return $this->resultIteratorFactory->create($queryObject, $criteria);
    }
}
