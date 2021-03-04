<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Imatic\Testing\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    protected function setUp(): void
    {
        static::createClient();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return self::$container->get(EntityManagerInterface::class);
    }
}
