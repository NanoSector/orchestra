<?php
declare(strict_types=1);

namespace Domain\Endpoint\Driver;

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