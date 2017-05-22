<?php

namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Utils\BundleNameFinder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

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

                if (array_key_exists('alias', $attributes)) {
                    $handlerRepositoryDef->addMethodCall('addHandler', [
                        $attributes['alias'],
                        new Reference($id),
                        $finder->find($definition->getClass()),
                    ]);
                }

                $handlerRepositoryDef->addMethodCall('addHandler', [
                    $definition->getClass(),
                    new Reference($id),
                    $finder->find($definition->getClass()),
                ]);
            }
        }
    }
}
