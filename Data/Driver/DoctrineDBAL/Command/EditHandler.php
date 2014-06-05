<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema\Schema;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class EditHandler implements HandlerInterface
{
    /** @var Connection */
    private $connection;

    /** @var Schema */
    private $schema;

    public function __construct(Connection $connection, Schema $schema)
    {
        $this->connection = $connection;
        $this->schema = $schema;
    }

    public function handle(CommandInterface $command)
    {
        $table = $command->getParameter('table');
        $data = $command->getParameter('data');
        $id = $command->getParameter('id');

        $queryData = $this->schema->getQueryData($table, $data);

        $this->connection->update($queryData->getTable(), $queryData->getData(), $id, $queryData->getTypes());
    }
}
