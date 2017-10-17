<?php

namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DriverCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $drivers = $container->findTaggedServiceIds('imatic_data.driver');
        $driverRepositoryDef = $container->findDefinition('imatic_data.driver_repository');

        $ids = array_keys($drivers);
        foreach ($ids as $id) {
            $driverRepositoryDef->addMethodCall('add', [
                new Reference($id),
            ]);
        }
    }
}
