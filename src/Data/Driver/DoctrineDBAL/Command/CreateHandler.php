<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResult;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema\Schema;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CreateHandler implements HandlerInterface
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
        $data = $command->getParameter('data');
        $table = $command->getParameter('table');

        if (!\array_key_exists('id', $data)) {
            $data['id'] = $this->schema->getNextIdValue($table);
        }

        $queryData = $this->schema->getQueryData($table, $data);
        $this->connection->insert($queryData->getTable(), $queryData->getData(), $queryData->getTypes());

        return CommandResult::success()->set('result', $data['id'] !== null ? $data['id'] : $this->connection->lastInsertId());
    }
}
