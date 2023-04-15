<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Endpoint;


use Orchestra\Domain\Endpoint\Driver\Container\DriverFactory;
use Orchestra\Domain\Endpoint\Driver\DriverResponseInterface;
use Orchestra\Domain\Entity\Endpoint;
use Orchestra\Domain\Exception\EndpointDriverNotInstantiableException;
use Orchestra\Domain\Exception\EndpointExecutionFailedException;

readonly class EndpointClient
{
    public function __construct(
        private DriverFactory $driverFactory
    ) {
    }

    /**
     * @throws EndpointDriverNotInstantiableException
     * @throws EndpointExecutionFailedException
     */
    public function fetch(Endpoint $endpoint): DriverResponseInterface
    {
        $driver = $this->driverFactory->instantiateDriver($endpoint->getDriver());

        return $driver->fetch($endpoint);
    }
}