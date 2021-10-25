<?php
declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineDBAL\ResultNormalizer;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer\ResultNormalizerImpl;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

class ResultNormalizerImplTest extends WebTestCase
{
    public function testNormalizeMainTableWithoutAliasing()
    {
        $connection = $this->getConnection();
        $resultNormalizer = $this->getResultNormalizer();

        $statement = $connection->executeQuery(
            <<<'SQL'
SELECT
    u.id,
    u.name,
    u.activated,
    u.birth_date,
    u.favorite_day,
    u.favorite_time,
    u.hairs
FROM test_user u
WHERE id = 1
SQL
        )->getIterator();

        $result = $resultNormalizer->normalize($statement);

        $this->assertEquals(
            [
                [
                    'id' => 1,
                    'name' => 'Adam',
                    'activated' => true,
                    'birth_date' => new \DateTime('1990-01-01 00:00:00'),
                    'favorite_day' => new \DateTime('2013-05-03'),
                    'favorite_time' => new \DateTime('1970-01-01 05:32:00'),
                    'hairs' => 'short',
                ],
            ],
            $result
        );
    }

    public function testNormalizeMainTableWithAliasing()
    {
        $connection = $this->getConnection();
        $resultNormalizer = $this->getResultNormalizer();

        $statement = $connection->executeQuery(
            <<<'SQL'
SELECT
    u.id,
    u.name,
    u.activated,
    u.birth_date,
    u.favorite_day,
    u.favorite_time as renamed_fav_time,
    u.hairs
FROM test_user u
WHERE id = 1
SQL
        )->getIterator();

        $result = $resultNormalizer->normalize($statement);

        $this->assertEquals(
            [
                [
                    'id' => 1,
                    'name' => 'Adam',
                    'activated' => true,
                    'birth_date' => new \DateTime('1990-01-01 00:00:00'),
                    'favorite_day' => new \DateTime('2013-05-03'),
                    'renamed_fav_time' => new \DateTime('1970-01-01 05:32:00'),
                    'hairs' => 'short',
                ],
            ],
            $result
        );
    }

    private function getResultNormalizer()
    {
        return self::$container->get('imatic_data.tests.' . ResultNormalizerImpl::class);
    }

    /**
     * @return Connection
     */
    private function getConnection()
    {
        return self::$container->get('doctrine.dbal.default_connection');
    }
}
