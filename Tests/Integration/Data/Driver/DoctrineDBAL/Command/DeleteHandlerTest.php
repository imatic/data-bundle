<?php

namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineDBAL\Command;

use Doctrine\DBAL\Portability\Connection;
use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\DeleteHandler;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DeleteHandlerTest extends WebTestCase
{
    public function testDeleteHandlerShouldDeleteData()
    {
        $this->assertEquals(1, $this->findUserCount(1));

        $commandParameters = [
            'table' => 'user',
            'id' => ['id' => '1'],
        ];
        $command = new Command('handler', $commandParameters);

        $this->getDeleteHandler()->handle($command);

        $this->assertEquals(0, $this->findUserCount(1));
    }

    /**
     * @return DeleteHandler
     */
    private function getDeleteHandler()
    {
        return $this->container->get('imatic_data.driver.doctrine_dbal.command.delete');
    }

    private function findUserCount($id)
    {
        return $this->getConnection()->createQueryBuilder()
            ->select('COUNT(u.id) count')
            ->from('user', 'u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetch()['count'];
    }

    /**
     * @return Connection
     */
    private function getConnection()
    {
        return $this->container->get('doctrine.dbal.default_connection');
    }
}
