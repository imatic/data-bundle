<?php
declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Types\Type;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema\Schema;

/**
 * @deprecated This result normalizer is deprecated and is there just for backward compatibility reasons.
 */
class DeprecatedResultNormalizer implements ResultNormalizer
{
    /** @var Schema */
    private $schema;

    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection, Schema $schema)
    {
        $this->connection = $connection;
        $this->schema = $schema;
    }

    public function normalize(PDOStatement $statement)
    {
        $tables = [];
        $quoteCharacter = $this->connection->getDatabasePlatform()->getIdentifierQuoteCharacter();
        \preg_match(\sprintf('/FROM *%1$s?(\w+)%1$s?/i', $quoteCharacter), $statement->queryString, $tables);

        if (\count($tables) !== 2) {
            throw new \LogicException(\sprintf('Found %d tables in queryString "%s", but 1 expected.', \max([0, \count($tables) - 1]), $statement->queryString));
        }

        $columnTypes = $this->schema->getColumnTypes($tables[1]);
        $platform = $this->connection->getSchemaManager()->getDatabasePlatform();

        $normalizedResult = [];
        $result = $statement->fetchAll();
        $resultCount = \count($result);
        for ($i = 0; $i < $resultCount; ++$i) {
            foreach ($result[$i] as $column => $value) {
                if (isset($columnTypes[$column])) {
                    $normalizedResult[$i][$column] = Type::getType($columnTypes[$column])->convertToPHPValue($value, $platform);
                } else {
                    $normalizedResult[$i][$column] = $value;
                }
            }
        }

        return $normalizedResult;
    }
}
