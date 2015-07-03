<?php

namespace Nuxia\Bundle\NuxiaBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('nuxia');

        //@formater:off
        $rootNode
            ->children()
                ->arrayNode('mailer')
                    ->canBeDisabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('from')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('email')->defaultValue('noreply@nuxia.fr')->cannotBeEmpty()->end()
                                ->scalarNode('name')->defaultValue('Nuxia')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('validator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('reserved_words')
                            ->defaultValue(array())->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('security')
                    ->canBeDisabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('disable_password')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('media')
                    ->addDefaultsIfNotSet()
                        ->children()
                        ->scalarNode('thumbnail_path')->defaultValue('/bundles/nuxia/images/thumbnail.png')->end()
                    ->end()
                ->end()
                ->arrayNode('paginator')
                    ->addDefaultsIfNotSet()
                    ->canBeDisabled()
                ->end()
            ->end()
        ->end();
        //@formater:on

        return $treeBuilder;
    }
}
