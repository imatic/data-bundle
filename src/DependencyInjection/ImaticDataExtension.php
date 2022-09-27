<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\DependencyInjection;

use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\DisplayCriteriaReader;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\CommandHandlerCompilerPass;
use Imatic\Bundle\DataBundle\DependencyInjection\Compiler\FilterCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ImaticDataExtension extends Extension
{
    /**
     * @param mixed[] $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setAlias(DisplayCriteriaReader::class, $config['display_criteria_reader']);

        if (!$container->hasParameter('imatic_data.base_file_path')) {
            $container->setParameter('imatic_data.base_file_path', \realpath($container->getParameter('kernel.project_dir')));
        }

        $container->registerForAutoconfiguration(FilterInterface::class)
            ->addTag(FilterCompilerPass::FILTER_TAG);

        $container->registerForAutoconfiguration(HandlerInterface::class)
            ->addTag(CommandHandlerCompilerPass::HANDLER_TAG);

        $this->processUnaccentLower($config['unaccent_lower'], $container);
        $this->processPager($config['pager'], $container);
        $this->processColumnTypes($config['column_types'], $container);
    }

    /**
     * @param mixed[] $unaccentLowerConfig
     */
    private function processUnaccentLower(array $unaccentLowerConfig, ContainerBuilder $container): void
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
     * @param mixed[] $pagerConfig
     */
    private function processPager(array $pagerConfig, ContainerBuilder $container): void
    {
        $pagerFactoryDef = $container->findDefinition(PagerFactory::class);
        $pagerFactoryDef->addMethodCall('setDefaultLimit', [$pagerConfig['default_limit']]);
    }

    /**
     * @param mixed[] $columnTypesConfig
     */
    private function processColumnTypes(array $columnTypesConfig, ContainerBuilder $container): void
    {
        $container->setParameter('imatic_data.driver.doctrine_dbal.schema.column_types', $columnTypesConfig);
    }
}
