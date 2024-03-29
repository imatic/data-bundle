<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaQueryBuilderDelegate;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DisplayCriteriaQueryBuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $displayCriteriaQueryBuilderDef = $container->findDefinition(DisplayCriteriaQueryBuilderDelegate::class);
        $displayCriteriaQueryBuilderServices = $container->findTaggedServiceIds('imatic_data.display_criteria_query_builder');

        $displayCriteriaQueryBuilders = [];
        $ids = \array_keys($displayCriteriaQueryBuilderServices);
        foreach ($ids as $id) {
            $displayCriteriaQueryBuilders[] = new Reference($id);
        }

        $displayCriteriaQueryBuilderDef->addMethodCall('setBuilders', [
            $displayCriteriaQueryBuilders,
        ]);
    }
}
