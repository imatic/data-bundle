<?php

namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Utils\BundleNameFinder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CommandHandlerCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $handlers = $container->findTaggedServiceIds('imatic_data.handler');
        $handlerRepositoryDef = $container->findDefinition('imatic_data.command_handler_repository');

        $finder = new BundleNameFinder($container->getParameter('kernel.bundles'));

        foreach ($handlers as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition = $container->getDefinition($id);
                $handlerRepositoryDef->addMethodCall('addLazyHandler', [
                    $attributes['alias'],
                    $id,
                    $finder->find($definition->getClass()),
                ]);
            }
        }
    }
}
