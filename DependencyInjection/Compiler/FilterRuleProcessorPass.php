<?php
namespace Imatic\Bundle\DataBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FilterRuleProcessorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $filterRuleProcessorDef = $container->findDefinition('imatic_data.filter_rule_processor');
        $filterRuleProcessorServices = $container->findTaggedServiceIds('imatic_data.filter_rule_processor');

        $filterRuleProcessors = [];
        foreach ($filterRuleProcessorServices as $id => $tags) {
            foreach ($tags as $tag) {
                $filterRuleProcessors[] = [
                    'id' => $id,
                    'priority' => isset($tag['priority'])
                        ? $tag['priority']
                        : 0,
                ];
            }
        }

        \usort($filterRuleProcessors, function ($a, $b) {
            return $a['priority'] > $b['priority']
                ? -1
                : (
                    $a['priority'] < $b['priority']
                    ? 1
                    : 0
                );
        });

        foreach ($filterRuleProcessors as &$filterRuleProcessor) {
            $filterRuleProcessor = new Reference($filterRuleProcessor['id']);
        }

        $filterRuleProcessorDef->addMethodCall('setFilterRuleProcessors', [
            $filterRuleProcessors,
        ]);
    }
}
