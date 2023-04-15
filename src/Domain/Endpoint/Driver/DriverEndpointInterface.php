<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Endpoint\Driver;

interface DriverEndpointInterface
{
    /**
     * Return the HTTP endpoint to connect to.
     */
    public function getUrl(): string;

    /**
     * Return a list of driver options. The driver is in charge of validating these.
     *
     * @return array
     */
    public function getDriverOptions(): array;
}