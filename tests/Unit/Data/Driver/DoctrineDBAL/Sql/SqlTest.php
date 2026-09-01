<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Unit\Data\Driver\DoctrineDBAL\Sql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Sql\Sql;
use PHPUnit\Framework\TestCase;

class SqlTest extends TestCase
{
    public function testConcatShouldUseConcatenationOperatorOnSqlite()
    {
        $this->assertSame('a || b', Sql::concat(['a', 'b'], $this->createConnection(new SQLitePlatform())));
    }

    public function testConcatShouldUseConcatenationOperatorOnSqlitePlatformDescendant()
    {
        $platform = new class() extends SQLitePlatform {};

        $this->assertSame('a || b', Sql::concat(['a', 'b'], $this->createConnection($platform)));
    }

    public function testConcatShouldUseConcatFunctionOnOtherPlatforms()
    {
        $this->assertSame('CONCAT(a, b)', Sql::concat(['a', 'b'], $this->createConnection(new PostgreSQLPlatform())));
    }

    private function createConnection(AbstractPlatform $platform): Connection
    {
        $connection = $this->createMock(Connection::class);
        $connection
            ->method('getDatabasePlatform')
            ->willReturn($platform);

        return $connection;
    }
}
