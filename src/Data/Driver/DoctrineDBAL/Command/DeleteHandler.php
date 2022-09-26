<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DeleteHandler implements HandlerInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(CommandInterface $command)
    {
        $table = $command->getParameter('table');
        $id = $command->getParameter('id');

        try {
            $this->connection->delete($table, $id);

            return CommandResult::success();
        } catch (ForeignKeyConstraintViolationException $e) {
            return CommandResult::error('constraint_violation');
        }
    }
}
