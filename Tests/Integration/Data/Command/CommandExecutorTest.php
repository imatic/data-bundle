<?php
namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutor;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CommandExecutorTest extends WebTestCase
{
    private $client;
    private $container;

    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
    }

    public function testGivenCommandShouldBeSuccessfullyExecuted()
    {
        /* @var $user User */
        $user = $this->getUserRepository()->findOneByName('Eva');

        // guard
        $this->assertTrue($user->isActivated());

        $command = new Command('user.deactivate', ['id' => $user->getId()]);
        $result = $this->getCommandExecutor()->execute($command);
        $this->assertTrue($result->isSuccessful());

        $this->assertFalse($user->isActivated());
    }

    /**
     * @return EntityRepository
     */
    public function getUserRepository()
    {
        return $this->getEntityManager()->getRepository('AppImaticDataBundle:User');
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @return CommandExecutor
     */
    private function getCommandExecutor()
    {
        return $this->container->get('imatic_data.command_executor');
    }
}
