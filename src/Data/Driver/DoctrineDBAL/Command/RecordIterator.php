<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Exception;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryExecutor;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultIteratorFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\ArrayRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SelectableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\ResultIterator;
use Psr\Log\LoggerInterface;

class RecordIterator
{
    protected QueryExecutor $queryExecutor;
    protected ResultIteratorFactory $resultIteratorFactory;
    protected ?LoggerInterface $logger;

    public function __construct(
        QueryExecutor $queryExecutor,
        ResultIteratorFactory $resultIteratorFactory,
        LoggerInterface $logger = null
    ) {
        $this->queryExecutor = $queryExecutor;
        $this->resultIteratorFactory = $resultIteratorFactory;
        $this->logger = $logger;
    }

    public function each(RecordIteratorArgs $recordIteratorArgs): CommandResult
    {
        $records = $this->getRecords($recordIteratorArgs->getCommand(), $recordIteratorArgs->getQueryObject());

        return $this->passValues($records, $recordIteratorArgs->getCallback());
    }

    public function eachIdentifier(RecordIteratorArgs $recordIteratorArgs): CommandResult
    {
        $ids = $this->getRecordIds($recordIteratorArgs->getCommand(), $recordIteratorArgs->getQueryObject());

        return $this->passValues($ids, $recordIteratorArgs->getCallback());
    }

    /**
     * @param mixed $values
     */
    protected function passValues($values, callable $callback): CommandResult
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

            if (null !== $this->logger) {
                $this->logger->error('An exception was thrown when passing values.', ['exception' => $e]);
            }

            $return = CommandResult::error('batch_error', [], $e);
            if (isset($result)) {
                $return->addMessages($result->getMessages());
            }

            return $return;
        }

        return CommandResult::success('batch_success', ['%count%' => \count($values)]);
    }

    /**
     * @return mixed[]
     */
    protected function getRecordIds(CommandInterface $command, QueryObjectInterface $queryObject): array
    {
        if (!$queryObject instanceof SelectableQueryObjectInterface) {
            throw new \InvalidArgumentException(\sprintf(
                '%s have to be instance of "%s"',
                \get_class($queryObject),
                SelectableQueryObjectInterface::class
            ));
        }

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

    protected function getRecords(CommandInterface $command, QueryObjectInterface $queryObject): ResultIterator
    {
        if (!$queryObject instanceof SelectableQueryObjectInterface) {
            throw new \InvalidArgumentException(\sprintf(
                '%s have to be instance of "%s"',
                \get_class($queryObject),
                SelectableQueryObjectInterface::class
            ));
        }

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
