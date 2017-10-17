<?php

namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DisplayCriteriaQueryBuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $displayCriteriaQueryBuilderDef = $container->findDefinition('imatic_data.display_criteria_query_builder');
        $displayCriteriaQueryBuilderServices = $container->findTaggedServiceIds('imatic_data.display_criteria_query_builder');

        $displayCriteriaQueryBuilders = [];
        $ids = array_keys($displayCriteriaQueryBuilderServices);
        foreach ($ids as $id) {
            $displayCriteriaQueryBuilders[] = new Reference($id);
        }

        $displayCriteriaQueryBuilderDef->addMethodCall('setBuilders', [
            $displayCriteriaQueryBuilders,
        ]);
    }
}
