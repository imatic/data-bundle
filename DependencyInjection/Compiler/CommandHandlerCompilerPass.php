<?php
namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

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

        foreach ($handlers as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $handlerRepositoryDef->addMethodCall('addHandler', [
                    $attributes['alias'],
                    new Reference($id),
                ]);
            }
        }
    }
}
