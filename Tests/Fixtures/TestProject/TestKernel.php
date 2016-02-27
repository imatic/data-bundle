<?php

namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject;

use Imatic\Bundle\TestingBundle\Test\TestKernel as BaseTestKernel;

class TestKernel extends BaseTestKernel
{
    public function __construct()
    {
        $this->loadConfigFromPhpUnit();

        parent::__construct();
    }

    public function registerBundles()
    {
        $parentBundles = parent::registerBundles();

        $bundles = [
            new \Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new \Imatic\Bundle\FormBundle\ImaticFormBundle(),
            new \Imatic\Bundle\DataBundle\ImaticDataBundle(),
            new \Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\AppImaticDataBundle(),
        ];

        return array_merge($parentBundles, $bundles);
    }

    /**
     * Load config from phpunit.xml if no phpunit context exists.
     */
    public function loadConfigFromPhpUnit()
    {
        $files = ['phpunit.xml', 'phpunit.xml.dist'];
        array_map(function ($file) {
            $file = __DIR__ . '/../../../' . $file;
            if (file_exists($file)) {
                $element = simplexml_load_file($file);
                foreach ($element->xpath('/phpunit/php/const') as $const) {
                    if (!defined($const['name'])) {
                        define($const['name'], $const['value']);
                    }
                }
            }
        }, $files);
    }
}
