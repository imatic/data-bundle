<?php
namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FilterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $filterServices = $container->findTaggedServiceIds('imatic_data.filter');
        $filterFactoryDef = $container->findDefinition('imatic_data.filter_factory');

        $filters = [];
        foreach ($filterServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attribute) {
                $filters[$attribute['alias']] = $id;
            }
        }

        $filterFactoryDef->addMethodCall('setFilters', [
            $filters,
        ]);
    }
}
