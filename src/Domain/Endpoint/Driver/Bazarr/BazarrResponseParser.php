<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint\Driver\Bazarr;

use Doctrine\Common\Collections\ArrayCollection;
use Orchestra\Domain\Exception\EndpointExecutionFailedException;
use Orchestra\Domain\Metric\Parser\Builder\ParserBuilder;
use Orchestra\Domain\Metric\Parser\ResponseStructure;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class BazarrResponseParser
{
    public function __construct(private ParserBuilder $builder)
    {
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function parse(ResponseInterface $response): BazarrDriverResponse
    {
        try {
            $payload = $response->toArray();
        } catch (ExceptionInterface $e) {
            throw new EndpointExecutionFailedException('Payload failed to decode', 0, $e);
        }

        $structure = new ResponseStructure([
            'data' => $this->builder->required([
                'bazarr_version' => $this->builder->metric()->version('Bazarr'),
                'sonarr_version' => $this->builder->metric()->version('Sonarr'),
                'radarr_version' => $this->builder->metric()->version('Radarr'),
                'python_version' => $this->builder->metric()->version('Python'),
            ]),
        ]);

        $metrics = $structure->parseMetrics($payload);

        return new BazarrDriverResponse($response, new ArrayCollection($metrics));
    }
}
