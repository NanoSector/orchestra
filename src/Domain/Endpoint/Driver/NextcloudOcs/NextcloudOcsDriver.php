<?php

declare(strict_types=1);

namespace Domain\Endpoint\Driver\NextcloudOcs;

use Domain\Endpoint\Driver\AbstractDriver;
use Domain\Endpoint\Driver\DriverEndpointInterface;
use Domain\Exception\EndpointExecutionFailedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NextcloudOcsDriver extends AbstractDriver
{
    private HttpClientInterface $client;
    private NextcloudOcsResponseParser $responseParser;

    public function __construct(HttpClientInterface $client, NextcloudOcsResponseParser $responseParser)
    {
        $this->client = $client;
        $this->responseParser = $responseParser;
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function fetch(DriverEndpointInterface $endpoint): NextcloudOcsResponse
    {
        $options = $endpoint->getDriverOptions();
        $options = $this->sanitizeConfiguration($options);

        $url = $endpoint->getUrl();

        try {
            $response = $this->client->request('GET', $url, [
                'query' => [
                    'format' => 'json',
                ],
                'headers' => [
                    'NC-Token' => $options['token'],
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
            'token' => 'Will be passed as NC-Token header',
        ];
    }
}