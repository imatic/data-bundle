<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryExecutor;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;

/**
 * @deprecated In favor of RecordIterator or BatchHandler
 */
abstract class AbstractBatchHandler
{
    /**
     * @var QueryExecutor
     */
    protected $queryExecutor;

    /**
     * @var ArrayDisplayCriteriaFactory
     */
    protected $displayCriteriaFactory;

    /**
     * @var FilterFactory
     */
    protected $filterFactory;

    /**
     * @param CommandInterface $command
     *
     * @throws \Exception
     *
     * @return CommandResultInterface|bool|void
     */
    public function handle(CommandInterface $command)
    {
        $handleAll = $command->getParameter('selectedAll');
        $ids = $command->getParameter('selected');

        try {
            $this->queryExecutor->beginTransaction();

            if ($handleAll) {
                $criteria = \json_decode($command->getParameter('query'), true);
                $this->displayCriteriaFactory->setAttributes($criteria);
                $displayCriteria = $this->displayCriteriaFactory->createCriteria([
                    'filter' => $this->filterFactory->create($criteria['filter_type']),
                ]);
                $displayCriteria->getPager()->disable();

                $this->handleAll($displayCriteria);
            } else {
                foreach ($ids as $id) {
                    $result = $this->handleOne($id);

                    if (!$result->isSuccessful()) {
                        throw new \Exception();
                    }
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

        return CommandResult::success('batch_success', ['%count%' => \count($ids)]);
    }

    /**
     * @param mixed $id
     *
     * @return CommandResultInterface
     */
    abstract protected function handleOne($id);

    /**
     * @param DisplayCriteriaInterface $displayCriteria
     *
     * @return CommandResultInterface
     */
    abstract protected function handleAll(DisplayCriteriaInterface $displayCriteria);
}
