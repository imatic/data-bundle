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

        $commandParrameters = [
            'table' => 'user',
            'data' => [
                'name' => 'new-user',
                'activated' => true,
                'birthDate' => '1985-05-03',
                'hairs' => 'nice',
            ],
        ];
        $command = new Command('handler', $commandParrameters);

        $this->getCreateHandler()->handle($command);

        $newUser = $this->findUserByName('new-user');
        $this->assertEquals('new-user', $newUser['name']);
        $this->assertEquals(true, $newUser['activated']);
        $this->assertEquals('1985-05-03', $newUser['birthDate']);
        $this->assertEquals('nice', $newUser['hairs']);
    }

    /**
     * @return CreateHandler
     */
    private function getCreateHandler()
    {
        return $this->container->get('imatic_data.driver.doctrine_dbal.command.create');
    }

    private function findUserByName($name)
    {
        return $this->getConnection()->createQueryBuilder()
            ->select('u.id, u.name, u.activated, u.birthDate, u.hairs')
            ->from('user', 'u')
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
