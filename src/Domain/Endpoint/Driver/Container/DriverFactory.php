<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint\Driver\Container;

use Orchestra\Domain\Endpoint\Driver\DriverEnum;
use Orchestra\Domain\Endpoint\Driver\DriverInterface;
use Orchestra\Domain\Exception\EndpointDriverNotInstantiableException;
use Symfony\Component\HttpKernel\KernelInterface;

readonly class DriverFactory
{
    public function __construct(private KernelInterface $kernel)
    {
    }

    /**
     * @throws EndpointDriverNotInstantiableException
     */
    public function instantiateDriver(DriverEnum $driver): DriverInterface
    {
        $container = $this->kernel->getContainer();

        if (!$container->has($driver->getContainerDefinition())) {
            throw new EndpointDriverNotInstantiableException(
                sprintf(
                    'Driver %s not found in the container (looked for definition %s - is it public?)',
                    $driver->name,
                    $driver->getContainerDefinition()
                )
            );
        }

        $driverInstance = $container->get($driver->getContainerDefinition());

        if (!$driverInstance instanceof DriverInterface) {
            throw new EndpointDriverNotInstantiableException(
                sprintf(
                    'Driver %s with definition %s was found but did not result in an instance of %s',
                    $driver->name,
                    $driver->getContainerDefinition(),
                    DriverInterface::class
                )
            );
        }

        return $driverInstance;
    }
}
