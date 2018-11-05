<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('imatic_data');

        $rootNode
            ->children()
                ->scalarNode('display_criteria_reader')
                    ->info('Id of the service (e.g. "Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\ExtJsReader")')
                    ->defaultValue('Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\RequestQueryReader')
                ->end()
                ->arrayNode('pager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_limit')->defaultValue(100)->end()
                    ->end()
                ->end()
                ->variableNode('column_types')
                    ->info('Array of table names pointing to array of their columns with specified types as their values')
                    ->defaultValue([])
                ->end()
                ->arrayNode('unaccent_lower')
                    ->info(
                        <<<'INFO'
Configuration of SQL function name for contains, not contains operators (it's always unaccent_lower for DQL).
See https://stackoverflow.com/questions/9243322/postgres-accent-insensitive-like-search-in-rails-3-1-on-heroku
for example on how such function might look like.
INFO
                    )
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultValue(false)->end()
                        ->scalarNode('function_name')->defaultValue('unaccent_lower')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
