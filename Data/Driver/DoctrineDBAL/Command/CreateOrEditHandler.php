<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareTrait;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query\RecordIdQuery;
use Imatic\Bundle\DataBundle\Data\Query\NoResultException;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CreateOrEditHandler implements HandlerInterface, CommandExecutorAwareInterface
{
    use CommandExecutorAwareTrait;

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
        $tableName = $command->getParameter('table');
        $data = $command->getParameter('data');
        $columnValues = $command->getParameter('columnValues');

        $id = null;
        try {
            $query = new RecordIdQuery($tableName, $columnValues);
            $id = $this->queryExecutor->execute($query);
        } catch (NoResultException $ex) {
        }

        $command = null;
        if ($id) {
            $command = new Command(EditHandler::class, [
                'table' => $tableName,
                'data' => $data,
                'id' => $id,
            ]);
        } else {
            $command = new Command(CreateHandler::class, [
                'table' => $tableName,
                'data' => $data,
            ]);
        }

        return $this->commandExecutor->execute($command);
    }
}
