<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query\SoftDeleteQuery;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class SoftDeleteHandler implements HandlerInterface
{
    /**
     * @var QueryExecutorInterface
     */
    private $queryExecutor;

    public function __construct(QueryExecutorInterface $queryExecutor)
    {
        $this->queryExecutor = $queryExecutor;
    }

    public function handle(CommandInterface $command)
    {
        $table = $command->getParameter('table');
        $ids = $command->hasParameter('ids') ? $command->getParameter('ids') : [];
        if ($command->hasParameter('id')) {
            $ids[] = $command->getParameter('id');
        }

        if (\count($ids) === 0) {
            return;
        }

        $this->queryExecutor->execute(new SoftDeleteQuery($table, $ids));
    }
}
