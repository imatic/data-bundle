<?php

namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineDBAL\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CreateOrEditHandlerTest extends WebTestCase
{
    public function testRecordShouldBeEditedIfExistsAlready()
    {
        // guard
        $originalUser = $this->getUser(1);
        $this->assertNotNull($originalUser);
        $this->assertEquals('Adam', $originalUser->getName());
        $this->assertEquals('short', $originalUser->getHairs());

        $command = new Command('imatic_data.doctrine_dbal.generic_create_or_edit', [
            'table' => 'user',
            'data' => [
                'id' => 1,
                'name' => 'new-one',
            ],
            'columnValues' => [
                'id' => 1,
            ],
        ]);
        $this->getCommandExecutor()->execute($command);

        $editedUser = $this->getUser(1);
        $this->assertNotNull($editedUser);
        $this->assertEquals('new-one', $editedUser->getName());
        $this->assertEquals('short', $editedUser->getHairs());
    }

    public function testRecordShouldBeCreatedIfItDoesntExists()
    {
        // guard
        $originalUser = $this->getUser(100);
        $this->assertNull($originalUser);

        $command = new Command('imatic_data.doctrine_dbal.generic_create_or_edit', [
            'table' => 'user',
            'data' => [
                'id' => 100,
                'name' => 'new-one',
            ],
            'columnValues' => [
                'id' => 1,
            ],
        ]);
        $this->getCommandExecutor()->execute($command);

        $editedUser = $this->getUser(100);
        $this->assertNotNull($editedUser);
        $this->assertEquals('new-one', $editedUser->getName());
    }

    /**
     * @return CommandExecutorInterface
     */
    private function getCommandExecutor()
    {
        return $this->container->get('imatic_data.command_executor');
    }

    /**
     * @return User
     */
    private function getUser($id)
    {
        $this->getEntityManager()->clear();

        return $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->find($id);
    }
}