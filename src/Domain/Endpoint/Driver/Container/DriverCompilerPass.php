<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint\Driver\Container;

use Orchestra\Domain\Endpoint\Driver\DriverEnum;
use Orchestra\Domain\Exception\EndpointDriverNotInstantiableException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

readonly class DriverCompilerPass implements CompilerPassInterface
{
    /**
     * @throws EndpointDriverNotInstantiableException
     */
    public function process(ContainerBuilder $container): void
    {
        $drivers = DriverEnum::cases();

        foreach ($drivers as $driver) {
            // Verify that all drivers mentioned in the enum are publicly accessible through the container.
            if (!$container->hasDefinition($driver->value)) {
                throw new EndpointDriverNotInstantiableException(
                    sprintf(
                        "No public container definition found for driver %s (looked for definition %s)",
                        $driver->name,
                        $driver->getContainerDefinition()
                    )
                );
            }
        }
    }
}
