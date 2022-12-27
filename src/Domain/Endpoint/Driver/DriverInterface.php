<?php

declare(strict_types=1);

namespace Domain\Endpoint\Driver;

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
     * and setting null values for unrecognized options.
     *
     * @param array $options
     * @return array
     */
    public function sanitizeConfiguration(array $options): array;

    public function fetch(DriverEndpointInterface $endpoint): ResponseInterface;


}