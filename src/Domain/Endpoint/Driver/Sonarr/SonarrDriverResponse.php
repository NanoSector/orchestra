<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Endpoint\Driver\Sonarr;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Endpoint\Driver\AbstractDriverResponse;
use Domain\Endpoint\Driver\DriverResponseWithBodyInterface;
use Domain\Exception\EndpointExecutionFailedException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class SonarrDriverResponse extends AbstractDriverResponse implements DriverResponseWithBodyInterface
{
    public function __construct(private ResponseInterface $httpResponse, ArrayCollection $metrics)
    {
        parent::__construct($metrics);
    }

    /**
     * @throws EndpointExecutionFailedException
     */
    public function getResponseBody(): string
    {
        try {
            return $this->httpResponse->getContent(throw: false);
        } catch (TransportExceptionInterface $e) {
            return sprintf('Network error occurred (%s)', $e->getMessage());
        } catch (ExceptionInterface $e) {
            throw new EndpointExecutionFailedException(
                'Response body is invalid and unexpected exception was thrown',
                0,
                $e
            );
        }
    }
}