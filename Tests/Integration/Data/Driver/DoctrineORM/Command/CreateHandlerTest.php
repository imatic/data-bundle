<?php

namespace Imatic\Bundle\DataBundle\Tests\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\CreateHandler;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\Order;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

class CreateHandlerTest extends WebTestCase
{
    public function testHandlerShouldInsertData()
    {
        $user = new User();
        $user->setName('Fred');
        // guard
        $this->assertNull($user->getId());

        $this->getCreateHandler()->handle(new Command('handler', [
            'data' => $user,
        ]));

        $this->assertNotNull($user->getId());
    }

    public function testHandlerShouldInsertDataWithSuccessfulClassCheck()
    {
        $user = new User();
        $user->setName('Fred');
        // guard
        $this->assertNull($user->getId());

        $this->getCreateHandler()->handle(new Command('handler', [
            'data' => $user,
            'class' => User::class,
        ]));

        $this->assertNotNull($user->getId());
    }

    public function testHandlerShouldFailOnClassCheck()
    {
        $user = new User();
        $user->setName('Fred');
        // guard
        $this->assertNull($user->getId());

        $response = $this->getCreateHandler()->handle(new Command('handler', [
            'data' => $user,
            'class' => Order::class,
        ]));

        $this->assertNull($user->getId());
        $this->assertFalse($response->isSuccessful());
    }

    /**
     * @return CreateHandler
     */
    private function getCreateHandler()
    {
        return $this->container->get(CreateHandler::class);
    }
}
