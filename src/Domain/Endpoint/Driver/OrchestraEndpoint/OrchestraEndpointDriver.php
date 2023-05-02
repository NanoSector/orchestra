<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint\Driver\OrchestraEndpoint;

use Orchestra\Domain\Endpoint\Driver\AbstractDriver;
use Orchestra\Domain\Endpoint\Driver\DriverEndpointInterface;
use Orchestra\Domain\Exception\EndpointExecutionFailedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class OrchestraEndpointDriver extends AbstractDriver
{
    public function __construct(
        private HttpClientInterface $client,
        private OrchestraEndpointResponseParser $responseParser
    ) {
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function fetch(DriverEndpointInterface $endpoint): OrchestraEndpointDriverResponse
    {
        $options = $endpoint->getDriverOptions();
        $options = $this->sanitizeConfiguration($options);

        $url = $endpoint->getUrl();

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'X-Orchestra-Token' => $options['token'],
                ],
            ]);
        } catch (TransportExceptionInterface $e) {
            throw new EndpointExecutionFailedException('HTTP Client execution failed', 0, $e);
        }

        return $this->responseParser->parse($response);
    }

    public function getConfigurationSkeleton(): array
    {
        return [
            'token' => 'Will be passed as X-Orchestra-Token header',
        ];
    }
}
