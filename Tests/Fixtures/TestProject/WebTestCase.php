<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject;

use Doctrine\ORM\EntityManager;
use Imatic\Bundle\TestingBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WebTestCase extends BaseWebTestCase
{
    /** @var ContainerInterface */
    protected $container;

    protected function setUp()
    {
        $this->container = static::createClient()->getContainer();
    }

    /**
     * @return string
     */
    protected static function getKernelClass()
    {
        return 'Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\TestKernel';
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }
}
