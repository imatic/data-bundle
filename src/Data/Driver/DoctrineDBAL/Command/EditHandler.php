<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema\Schema;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class EditHandler implements HandlerInterface
{
    private Connection $connection;
    private Schema $schema;

    public function __construct(Connection $connection, Schema $schema)
    {
        $this->connection = $connection;
        $this->schema = $schema;
    }

    /**
     * @throws Exception
     */
    public function handle(CommandInterface $command): void
    {
        $table = $command->getParameter('table');
        $data = $command->getParameter('data');
        $id = $command->getParameter('id');

        $queryData = $this->schema->getQueryData($table, $data);

        $this->connection->update($queryData->getTable(), $queryData->getData(), $id, $queryData->getTypes());
    }
}
