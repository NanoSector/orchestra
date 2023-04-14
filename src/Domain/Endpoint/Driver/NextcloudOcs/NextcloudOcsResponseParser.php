<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Endpoint\Driver\NextcloudOcs;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Exception\EndpointExecutionFailedException;
use Domain\Metric\Parser\Builder\ParserBuilder;
use Domain\Metric\Parser\ResponseStructure;
use Domain\Metric\Parser\Specialized\PostgreSQLVersionNode;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class NextcloudOcsResponseParser
{

    public function __construct(private ParserBuilder $builder)
    {
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function parse(ResponseInterface $response): NextcloudOcsDriverResponse
    {
        try {
            $payload = $response->toArray();
        } catch (ExceptionInterface $e) {
            throw new EndpointExecutionFailedException('Payload failed to decode', 0, $e);
        }

        $structure = new ResponseStructure([
            'ocs' => $this->builder->required([
                'meta' => $this->builder->metric()->healthCheck(
                    'Nextcloud',
                    fn(array $data) => array_key_exists('status', $data) && $data['status'] === 'ok'
                ),
                'data' => [
                    'nextcloud' => [
                        'system' => [
                            'version' => $this->builder->metric()->version('Nextcloud'),
                        ]
                    ],
                    'server'    => [
                        'webserver' => $this->builder->metric()->specialized()->webserverHeader(),
                        'php'       => [
                            'version' => $this->builder->metric()->version('PHP'),
                        ],
                        'database'  => $this->builder->switch(
                            static fn($value) => is_array($value) ? $value['type'] ?? '' : '',
                            [
                                // TODO add support for more DBMS here
                                'pgsql' => [
                                    'version' => new PostgreSQLVersionNode(),
                                ]
                            ]
                        )

                    ]
                ]
            ])
        ]);

        $metrics = $structure->parseMetrics($payload);

        return new NextcloudOcsDriverResponse($response, new ArrayCollection($metrics));
    }
}