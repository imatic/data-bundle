<?php
namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FilterCompilerPass implements CompilerPassInterface
{
    public const FILTER_TAG = 'imatic_data.filter';

    /**
     * @param ContainerBuilder $container
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariables)
     */
    public function process(ContainerBuilder $container)
    {
        $factory = $container->findDefinition(FilterFactory::class);

        $filters = [];

        foreach ($container->findTaggedServiceIds(self::FILTER_TAG) as $id => $attributes) {
            $filters[$id] = new Reference($id);
        }

        $factory->replaceArgument(0, ServiceLocatorTagPass::register($container, $filters));
    }
}
