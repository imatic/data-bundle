<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Types\Type;

class ResultNormalizerImpl implements ResultNormalizer
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function normalize(PDOStatement $statement)
    {
        $result = $statement->fetchAll(\PDO::FETCH_NUM);
        if (empty($result)) {
            return $result;
        }

        $columnCount = $statement->columnCount();
        $meta = \array_map(
            function ($idx) use ($statement) {
                return $statement->getColumnMeta($idx);
            },
            \range(0, $columnCount - 1)
        );

        $platform = $this->connection->getDatabasePlatform();
        $normalizeRow = function (array $row) use ($meta, $platform) {
            $result = [];
            foreach ($row as $key => $val) {
                $result[$meta[$key]['name']] = Type::getType($platform->getDoctrineTypeMapping($meta[$key]['native_type']))
                    ->convertToPHPValue($val, $platform);
            }

            return $result;
        };

        return \array_map(
            $normalizeRow,
            $result
        );
    }
}
