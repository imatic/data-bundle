<?php

namespace Imatic\Bundle\DataBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('imatic_data');

        $rootNode
            ->children()
                ->scalarNode('display_criteria_reader')
                    ->info('Id of the service (e.g. "imatic_data.extjs_display_criteria_reader")')
                    ->defaultValue('imatic_data.request_query_display_criteria_reader')
                ->end()
                ->arrayNode('pager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_limit')->defaultValue(100)->end()
                    ->end()
                ->end()
                ->variableNode('column_types')
                    ->defaultValue([])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
