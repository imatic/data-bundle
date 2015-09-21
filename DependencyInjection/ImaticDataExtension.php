<?php

namespace Imatic\Bundle\DataBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ImaticDataExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setAlias('imatic_data.display_criteria_reader', $config['display_criteria_reader']);

        if (!$container->hasParameter('imatic_data.base_file_path')) {
            $container->setParameter('imatic_data.base_file_path', realpath(sprintf('%s/..', $container->getParameter('kernel.root_dir'))));
        }

        $this->processPager($config['pager'], $container);
        $this->processColumnTypes($config['column_types'], $container);
    }

    /**
     * @param array            $pagerConfig
     * @param ContainerBuilder $container
     */
    private function processPager(array $pagerConfig, ContainerBuilder $container)
    {
        $pagerFactoryDef = $container->findDefinition('imatic_data.pager_factory');
        $pagerFactoryDef->addMethodCall('setDefaultLimit', [$pagerConfig['default_limit']]);
    }

    /**
     * @param array            $columnTypesConfig
     * @param ContainerBuilder $container
     */
    private function processColumnTypes(array $columnTypesConfig, ContainerBuilder $container)
    {
        $container->setParameter('imatic_data.driver.doctrine_dbal.schema.column_types', $columnTypesConfig);
    }
}
