<?php
namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineDBAL\Command;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\CreateHandler;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CreateHandlerTest extends WebTestCase
{
    public function testHandlerShouldInsertData()
    {
        $this->assertFalse($this->findUserByName('new-user'));

        $commandParameters = [
            'table' => 'test_user',
            'data' => [
                'name' => 'new-user',
                'activated' => true,
                'birth_date' => new \DateTime('1985-05-03'),
                'favorite_day' => new \DateTime('1890-09-03'),
                'favorite_time' => new \DateTime('16:30'),
                'hairs' => 'nice',
            ],
        ];
        $command = new Command('handler', $commandParameters);

        $this->getCreateHandler()->handle($command);

        $newUser = $this->findUserByName('new-user');
        $this->assertEquals('new-user', $newUser['name']);
        $this->assertTrue($newUser['activated']);
        $this->assertEquals('1985-05-03 00:00:00', $newUser['birth_date']);
        $this->assertEquals('1890-09-03', $newUser['favorite_day']);
        $this->assertEquals('16:30:00', $newUser['favorite_time']);
        $this->assertEquals('nice', $newUser['hairs']);
    }

    /**
     * @return CreateHandler
     */
    private function getCreateHandler()
    {
        return $this->container->get(CreateHandler::class);
    }

    private function findUserByName($name)
    {
        return $this->getConnection()->createQueryBuilder()
            ->select('u.*')
            ->from('test_user', 'u')
            ->andWhere('u.name = :name')
            ->setParameter('name', $name)
            ->execute()
            ->fetch();
    }

    /**
     * @return Connection
     */
    private function getConnection()
    {
        return $this->container->get('doctrine.dbal.default_connection');
    }
}
