<?php
namespace Imatic\Bundle\DataBundle\DependencyInjection;

use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\DisplayCriteriaReader;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\CommandHandlerCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * ImaticDataExtension.
 */
class ImaticDataExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // @deprecated Will be removed in 4.0
        $container->setAlias('imatic_data.display_criteria_reader', $config['display_criteria_reader']);

        $container->setAlias(DisplayCriteriaReader::class, $config['display_criteria_reader']);

        if (!$container->hasParameter('imatic_data.base_file_path')) {
            $container->setParameter('imatic_data.base_file_path', \realpath(\sprintf('%s/..', $container->getParameter('kernel.root_dir'))));
        }

        $container->registerForAutoconfiguration(HandlerInterface::class)
            ->addTag(CommandHandlerCompilerPass::HANDLER_TAG);

        $this->processUnaccentLower($config['unaccent_lower'], $container);
        $this->processPager($config['pager'], $container);
        $this->processColumnTypes($config['column_types'], $container);
    }

    private function processUnaccentLower(array $unaccentLowerConfig, ContainerBuilder $container)
    {
        if (!$unaccentLowerConfig['enabled']) {
            return;
        }

        $container->setParameter(
            'imatic_data.doctrine.contains_operator_processor.function',
            $unaccentLowerConfig['function_name']
        );
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
