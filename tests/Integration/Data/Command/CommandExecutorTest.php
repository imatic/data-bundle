<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Command;

use Doctrine\ORM\EntityRepository;
use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutor;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler\UserDeactivateHandler;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CommandExecutorTest extends WebTestCase
{
    public function testGivenCommandShouldBeSuccessfullyExecuted()
    {
        /* @var $user User */
        $user = $this->getUserRepository()->findOneBy(['name' => 'Adam']);

        // guard
        $this->assertTrue($user->isActivated());

        $command = new Command(UserDeactivateHandler::class, ['id' => $user->getId()]);
        $result = $this->getCommandExecutor()->execute($command);
        $this->assertTrue($result->isSuccessful());

        $this->assertFalse($user->isActivated());
    }

    public function getUserRepository(): EntityRepository
    {
        return $this->getEntityManager()->getRepository(User::class);
    }

    private function getCommandExecutor(): CommandExecutor
    {
        return self::getContainer()->get(CommandExecutor::class);
    }
}
