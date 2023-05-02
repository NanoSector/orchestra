<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint\Driver\PlexMediaServer;

use Doctrine\Common\Collections\ArrayCollection;
use Orchestra\Domain\Exception\EndpointExecutionFailedException;
use Orchestra\Domain\Metric\Parser\Builder\ParserBuilder;
use Orchestra\Domain\Metric\Parser\ResponseStructure;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class PlexMediaServerResponseParser
{
    public function __construct(private ParserBuilder $builder)
    {
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function parse(ResponseInterface $response): PlexMediaServerDriverResponse
    {
        $attributes = [
            0 => 'version',
        ];

        try {
            $crawler = new Crawler($response->getContent());

            $payload = $crawler->extract($attributes);
        } catch (ExceptionInterface $e) {
            throw new EndpointExecutionFailedException('Payload failed to decode', 0, $e);
        }

        $attributeKeys = array_flip($attributes);

        $structure = new ResponseStructure([
            $attributeKeys['version'] => $this->builder->metric()->version('Plex Media Server'),
        ]);

        $metrics = $structure->parseMetrics($payload);

        return new PlexMediaServerDriverResponse($response, new ArrayCollection($metrics));
    }
}
