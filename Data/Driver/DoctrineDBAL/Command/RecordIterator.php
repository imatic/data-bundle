<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Exception;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultIteratorFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\ArrayRule;
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
        $records = $this->getRecords($recordIteratorArgs->getCommand(), $recordIteratorArgs->getQueryObject());

        return $this->passValues($records, $recordIteratorArgs->getCallback());
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
                $result = \call_user_func($callback, $value);

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

        return CommandResult::success('batch_success', ['%count%' => \count($values)]);
    }

    protected function getRecordIds(CommandInterface $command, QueryObjectInterface $queryObject)
    {
        $handleAll = $command->getParameter('selectedAll');
        if (!$handleAll) {
            return $command->getParameter('selected');
        }

        $results = $this->getRecords($command, $queryObject);

        $ids = [];
        foreach ($results as $result) {
            $ids[] = $result[$queryObject->getIdentifierFilterKey()];
        }

        return $ids;
    }

    protected function getRecords(CommandInterface $command, QueryObjectInterface $queryObject)
    {
        $handleAll = $command->getParameter('selectedAll');
        $criteria = \json_decode($command->getParameter('query'), true);
        $filter = null;

        if (!$handleAll) {
            $filter = $this->resultIteratorFactory->createFilter($criteria);
            if (!$filter->has($queryObject->getIdentifierFilterKey())) {
                $filter[$queryObject->getIdentifierFilterKey()] = new ArrayRule($queryObject->getIdentifierFilterKey());
            }

            $criteria['filter'] = [
                $queryObject->getIdentifierFilterKey() => [
                    'value' => $command->getParameter('selected'),
                    'operator' => FilterOperatorMap::OPERATOR_IN,
                ],
            ];
        }

        return $this->resultIteratorFactory->create($queryObject, $criteria, $filter);
    }
}
