<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject;

use Imatic\Bundle\TestingBundle\Test\TestKernel as BaseTestKernel;

class TestKernel extends BaseTestKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $parentBundles = parent::registerBundles();

        $bundles = [
            new \Genemu\Bundle\FormBundle\GenemuFormBundle(),

            new \Imatic\Bundle\DataBundle\ImaticDataBundle(),
            new \Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\AppImaticDataBundle(),
        ];

        return array_merge($parentBundles, $bundles);
    }
}
