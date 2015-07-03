<?php

namespace Nuxia\Bundle\DynamicMediaBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('nuxia_dynamic_media');

        $rootNode
        //@formatter:off
            ->children()
                ->arrayNode('paginator')
                    ->addDefaultsIfNotSet()
                        ->children()
                             ->integerNode('limit')->min(1)->defaultValue(15)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        //@formatter:on
        return $treeBuilder;
    }
}
