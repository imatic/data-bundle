<?php

namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineDBAL\Command;

use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\EditHandler;
use Imatic\Bundle\DataBundle\Data\Command\Command;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class EditHandlerTest extends WebTestCase
{
    public function testHandlerShouldEditData()
    {
        $adam = $this->findUserById(1);
        $this->assertEquals([
            'id' => 1,
            'name' => 'Adam',
            'activated' => true,
            'birthDate' => '1990-01-01 00:00:00',
            'hairs' => 'short',
        ], $adam);

        $commandParrameters = [
            'table' => 'user',
            'id' => ['id' => 1],
            'data' => [
                'name' => 'name-change',
            ],
        ];
        $command = new Command('handler', $commandParrameters);

        $this->getEditHandler()->handle($command);

        $adam = $this->findUserById(1);
        $this->assertEquals([
            'id' => 1,
            'name' => 'name-change',
            'activated' => true,
            'birthDate' => '1990-01-01 00:00:00',
            'hairs' => 'short',
        ], $adam);
    }

    /**
     * @return EditHandler
     */
    private function getEditHandler()
    {
        return $this->container->get('imatic_data.driver.doctrine_dbal.command.edit');
    }

    private function findUserById($id)
    {
        return $this->getConnection()->createQueryBuilder()
            ->select('u.id, u.name, u.activated, u.birthDate, u.hairs')
            ->from('user', 'u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
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
