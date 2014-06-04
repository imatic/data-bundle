<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Doctrine\DBAL\Connection;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CreateHandler implements HandlerInterface
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(CommandInterface $command)
    {
        $data = $command->getParameter('data');
        $table = $command->getParameter('table');

        $this->connection->insert($table, $data);
    }
}
