<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Endpoint\Driver\Sonarr;

use Domain\Endpoint\Driver\AbstractDriver;
use Domain\Endpoint\Driver\DriverEndpointInterface;
use Domain\Exception\EndpointExecutionFailedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class SonarrDriver extends AbstractDriver
{

    public function __construct(
        private HttpClientInterface $client,
        private SonarrResponseParser $responseParser
    ) {
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function fetch(DriverEndpointInterface $endpoint): SonarrDriverResponse
    {
        $options = $endpoint->getDriverOptions();
        $options = $this->sanitizeConfiguration($options);

        $url = $endpoint->getUrl();

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'X-Api-Key' => $options['token'],
                ]
            ]);
        } catch (TransportExceptionInterface $e) {
            throw new EndpointExecutionFailedException('HTTP Client execution failed', 0, $e);
        }

        return $this->responseParser->parse($response);
    }

    public function getConfigurationSkeleton(): array
    {
        return [
            'token' => 'API token found in Sonarr\'s settings'
        ];
    }
}