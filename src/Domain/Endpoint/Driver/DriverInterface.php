<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Endpoint\Driver;

use Domain\Exception\EndpointExecutionFailedException;

interface DriverInterface
{
    /**
     * Create a configuration skeleton for new endpoints
     *
     * @return array
     */
    public function getConfigurationSkeleton(): array;

    /**
     * Sanitizes the configuration by removing any unrecognized options
     * and setting null values for unspecified options.
     *
     * @param array $options
     *
     * @return array
     */
    public function sanitizeConfiguration(array $options): array;

    /**
     * @throws EndpointExecutionFailedException
     */
    public function fetch(DriverEndpointInterface $endpoint): DriverResponseInterface;


}