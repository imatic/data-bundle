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

                if (\array_key_exists('alias', $attributes)) {
                    @\trigger_error(
                        'Alias attribute of "imatic_data.handler" tag is deprecated since version 3.1 and '
                        . 'will be removed in 4.0. Use service id or service alias as handler name instead.',
                        E_USER_DEPRECATED
                    );
                    $handlerRepositoryDef->addMethodCall('addLazyHandler', [
                        $attributes['alias'],
                        $id,
                        $finder->find($definition->getClass()),
                    ]);
                }

                $handlerRepositoryDef->addMethodCall('addLazyHandler', [
                    $id,
                    $id,
                    $finder->find($definition->getClass()),
                ]);
            }
        }
    }
}
