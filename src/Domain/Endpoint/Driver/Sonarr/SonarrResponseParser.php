<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Endpoint\Driver\Sonarr;

use Doctrine\Common\Collections\ArrayCollection;
use Orchestra\Domain\Exception\EndpointExecutionFailedException;
use Orchestra\Domain\Metric\Parser\Builder\ParserBuilder;
use Orchestra\Domain\Metric\Parser\ResponseStructure;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class SonarrResponseParser
{

    public function __construct(private ParserBuilder $builder)
    {
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function parse(ResponseInterface $response): SonarrDriverResponse
    {
        try {
            $payload = $response->toArray();
        } catch (ExceptionInterface $e) {
            throw new EndpointExecutionFailedException('Payload failed to decode', 0, $e);
        }

        $product = $payload['appName'] ?? 'Sonarr';

        $structure = new ResponseStructure([
            'version' => $this->builder->metric()->version($product),
        ]);

        $metrics = $structure->parseMetrics($payload);

        return new SonarrDriverResponse($response, new ArrayCollection($metrics));
    }
}