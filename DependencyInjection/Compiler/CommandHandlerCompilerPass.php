<?php
namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Data\Command\HandlerRepository;
use Imatic\Bundle\DataBundle\Utils\BundleNameFinder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CommandHandlerCompilerPass implements CompilerPassInterface
{
    public const HANDLER_TAG = 'imatic_data.handler';

    /**
     * @param ContainerBuilder $container
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariables)
     */
    public function process(ContainerBuilder $container)
    {
        $repository = $container->getDefinition(HandlerRepository::class);
        $finder = new BundleNameFinder($container->getParameter('kernel.bundles'));

        $locatableServices = [];

        foreach ($container->findTaggedServiceIds(self::HANDLER_TAG) as $id => $attributes) {
            $repository->addMethodCall('addBundleName', [
                $id,
                $finder->find($container->getDefinition($id)->getClass()),
            ]);

            $locatableServices[$id] = new Reference($id);
        }

        $repository->replaceArgument(0, ServiceLocatorTagPass::register($container, $locatableServices));
    }
}
