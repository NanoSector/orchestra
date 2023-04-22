<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Bundle\Endpoint\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * @internal
 */
class OrchestraEndpointExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $container->getDefinition('orchestra.controller.details')
                  ->setArgument(1, $config['token'])
                  ->setArgument(2, $config['custom_versions'])
                  ->setArgument(3, $config['include_php'])
                  ->setArgument(4, $config['include_symfony'])
                  ->setArgument(5, $config['include_webserver'])
                  ->setArgument(6, $config['include_operating_system']);
    }
}
