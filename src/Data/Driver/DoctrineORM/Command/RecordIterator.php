<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryExecutor;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ResultIterator;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ResultIteratorFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\ArrayRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SelectableQueryObjectInterface;
use RuntimeException;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RecordIterator
{
    protected QueryExecutor $queryExecutor;
    protected ResultIteratorFactory $resultIteratorFactory;

    public function __construct(
        QueryExecutor $queryExecutor,
        ResultIteratorFactory $resultIteratorFactory
    ) {
        $this->queryExecutor = $queryExecutor;
        $this->resultIteratorFactory = $resultIteratorFactory;
    }

    public function each(RecordIteratorArgs $recordIteratorArgs): CommandResult
    {
        $queryObject = $recordIteratorArgs->getQueryObject();
        $records = $this->getRecords($recordIteratorArgs->getCommand(), $queryObject);

        return $this->passValues($records, $recordIteratorArgs->getCallback(), $queryObject);
    }

    public function eachIdentifier(RecordIteratorArgs $recordIteratorArgs): CommandResult
    {
        $queryObject = $recordIteratorArgs->getQueryObject();
        $ids = $this->getRecordIds($recordIteratorArgs->getCommand(), $queryObject);

        return $this->passValues($ids, $recordIteratorArgs->getCallback(), $queryObject);
    }

    /**
     * @param mixed $values
     */
    protected function passValues($values, callable $callback, QueryObjectInterface $queryObject = null): CommandResult
    {
        try {
            $this->queryExecutor->getManager($queryObject)->beginTransaction();

            foreach ($values as $value) {
                $result = \call_user_func($callback, $value);

                if (!$result->isSuccessful()) {
                    throw new RuntimeException(
                        'Unsuccessful batch processing',
                        0,
                        $result->hasException() ? $result->getException() : null
                    );
                }
            }

            $this->queryExecutor->getManager($queryObject)->commit();
        } catch (\Exception $e) {
            $this->queryExecutor->getManager($queryObject)->rollback();

            $return = CommandResult::error('batch_error', [], $e);
            if (isset($result)) {
                $return->addMessages($result->getMessages());
            }

            return $return;
        }

        return CommandResult::success('batch_success', ['%count%' => \count($values)]);
    }

    /**
     * @return int[]
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
        $getter = \sprintf('get%s', \ucfirst($queryObject->getIdentifierFilterKey()));
        $ids = [];
        foreach ($results as $result) {
            $ids[] = $result->$getter();
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
