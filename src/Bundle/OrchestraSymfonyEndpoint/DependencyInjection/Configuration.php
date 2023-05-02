<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Bundle\Endpoint\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @internal
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('orchestra_endpoint');

        $treeBuilder->getRootNode()
                    ->children()
                    ->arrayNode('custom_versions')
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('name')->end()
                                ->scalarNode('version')->end()
                            ->end()
                        ->end()
                    ->end()
                    ->booleanNode('include_php')->defaultTrue()->end()
                    ->booleanNode('include_symfony')->defaultTrue()->end()
                    ->booleanNode('include_webserver')->defaultTrue()->end()
                    ->booleanNode('include_operating_system')->defaultTrue()->end()
                    ->scalarNode('token')->defaultValue('')->end()
                    ->end();

        return $treeBuilder;
    }
}
