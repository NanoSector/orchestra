<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint\Driver\OrchestraEndpoint;

use Doctrine\Common\Collections\ArrayCollection;
use Orchestra\Domain\Exception\EndpointExecutionFailedException;
use Orchestra\Domain\Metric\Parser\Builder\ParserBuilder;
use Orchestra\Domain\Metric\Parser\ResponseStructure;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class OrchestraEndpointResponseParser
{
    public function __construct(private ParserBuilder $builder)
    {
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function parse(ResponseInterface $response): OrchestraEndpointDriverResponse
    {
        try {
            $payload = $response->toArray();
        } catch (ExceptionInterface $e) {
            throw new EndpointExecutionFailedException('Payload failed to decode', 0, $e);
        }

        $structure = new ResponseStructure([
            'payload' => $this->builder->required([
                'healthy'  => $this->builder->metric()->healthCheck(
                    'Orchestra Endpoint',
                    fn() => true,
                ),
                'software' => $this->builder->each(fn($value) => [
                    'version' => $this->builder->metric()->version($value['name'] ?? 'Unknown'),
                ]),
            ]),
        ]);

        $metrics = $structure->parseMetrics($payload);

        return new OrchestraEndpointDriverResponse($response, new ArrayCollection($metrics));
    }
}
