<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Doctrine\DBAL\Connection;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DeleteHandler implements HandlerInterface
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(CommandInterface $command)
    {
        $table = $command->getParameter('table');
        $id = $command->getParameter('id');

        $this->connection->delete($table, $id);
    }
}
