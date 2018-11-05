<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject;

use Imatic\Testing\Test\TestKernel as BaseTestKernel;

class TestKernel extends BaseTestKernel
{
    public function registerBundles()
    {
        $parentBundles = parent::registerBundles();

        $bundles = [
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Imatic\Bundle\FormBundle\ImaticFormBundle(),
            new \Imatic\Bundle\DataBundle\ImaticDataBundle(),
            new \Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\AppImaticDataBundle(),
        ];

        return \array_merge($parentBundles, $bundles);
    }
}
