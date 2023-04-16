<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Endpoint\Driver\GenericPlaintextVersion;

use InvalidArgumentException;
use Orchestra\Domain\Endpoint\Driver\AbstractDriver;
use Orchestra\Domain\Endpoint\Driver\DriverEndpointInterface;
use Orchestra\Domain\Endpoint\GenericResponse\PlaintextVersionResponse;
use Orchestra\Domain\Exception\EndpointExecutionFailedException;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class GenericPlaintextVersionDriver extends AbstractDriver
{

    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function fetch(DriverEndpointInterface $endpoint): PlaintextVersionResponse
    {
        $options = $endpoint->getDriverOptions();

        $headers = array_filter(
            $options['headers'] ?? [],
            static fn($h, $k) => is_string($h) && is_string($k),
            ARRAY_FILTER_USE_BOTH
        );

        $options = $this->sanitizeConfiguration($options);
        $product = $options['product'];

        if (empty($product)) {
            throw new EndpointExecutionFailedException('No product name given in driver options');
        }

        $url = $endpoint->getUrl();

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => $headers
            ]);
        } catch (TransportExceptionInterface $e) {
            throw new EndpointExecutionFailedException('HTTP Client execution failed', 0, $e);
        }

        try {
            return new PlaintextVersionResponse($product, $response->getContent());
        } catch (HttpExceptionInterface|TransportExceptionInterface $e) {
            throw new EndpointExecutionFailedException('HTTP client got invalid response', $e->getCode(), $e);
        } catch (InvalidArgumentException $e) {
            throw new EndpointExecutionFailedException('Got invalid version string', $e->getCode(), $e);
        }
    }

    public function getConfigurationSkeleton(): array
    {
        return [
            'product' => 'Product name to set for this version',
            'headers' => ['Sample' => 'Header that will be sent with the GET request']
        ];
    }
}