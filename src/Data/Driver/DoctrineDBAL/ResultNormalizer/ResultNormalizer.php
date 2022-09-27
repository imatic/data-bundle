<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Types\Type;
use Imatic\Bundle\DataBundle\Data\Query\NormalizeResultQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;

class ResultNormalizer implements ResultNormalizerInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return mixed[]
     *
     * @throws Exception
     */
    public function normalize(QueryObjectInterface $queryObject, Result $result): array
    {
        $result = $result->fetchAllAssociative();

        if (!$queryObject instanceof NormalizeResultQueryObjectInterface) {
            return $result;
        }

        $map = $queryObject->getNormalizerMap();
        $platform = $this->connection->getDatabasePlatform();

        return array_map(function (array $row) use ($map, $platform) {
            foreach ($row as $key => $value) {
                if (\array_key_exists($key, $map)) {
                    $row[$key] = Type::getType($platform->getDoctrineTypeMapping($map[$key]))->convertToPHPValue($value, $platform);
                }
            }
            return $row;
        }, $result);
    }
}
