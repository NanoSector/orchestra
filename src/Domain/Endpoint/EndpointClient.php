<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Endpoint;


use Domain\Endpoint\Driver\Container\DriverFactory;
use Domain\Endpoint\Driver\DriverResponseInterface;
use Domain\Entity\Endpoint;
use Domain\Exception\EndpointDriverNotInstantiableException;
use Domain\Exception\EndpointExecutionFailedException;

class EndpointClient
{
    private DriverFactory $driverFactory;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driverFactory = $driverFactory;
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