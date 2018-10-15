<?php
namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FilterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $filterServices = $container->findTaggedServiceIds('imatic_data.filter');
        $filterFactoryDef = $container->findDefinition(FilterFactory::class);

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
