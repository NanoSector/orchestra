<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Bundle\Endpoint\Controller;

use Orchestra\Bundle\Endpoint\Domain\ServerSoftwareParser;
use Orchestra\Bundle\Endpoint\Domain\SoftwareDetails;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\ConfigDataCollector;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class OrchestraDetailsController
{
    public function __construct(
        private ConfigDataCollector $configDataCollector,
        private string $token,
        private array $customVersions,
        private bool $includePHP,
        private bool $includeSymfony,
        private bool $includeWebserver,
        private bool $includeOperatingSystem,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        // If simple token authentication is required, verify it
        if (!empty($this->token)) {
            $requestToken = $request->headers->get('X-Orchestra-Token')
                ?? $request->query->get('token');

            if (empty($requestToken) || $requestToken !== $this->token) {
                throw new HttpException(Response::HTTP_FORBIDDEN, 'Unauthorized');
            }
        }

        $this->configDataCollector->collect($request, new Response());
        $software = [];

        if ($this->includePHP && !empty($phpVersion = $this->configDataCollector->getPhpVersion())) {
            $software[] = new SoftwareDetails(
                name: 'PHP',
                versionString: $phpVersion
            );
        }

        if ($this->includeSymfony && !empty($symfonyVersion = $this->configDataCollector->getSymfonyVersion())) {
            $software[] = new SoftwareDetails(
                name: 'Symfony',
                versionString: $symfonyVersion
            );
        }

        if ($this->includeWebserver && !empty($serverVersion = $_SERVER['SERVER_SOFTWARE'])) {
            $parsed = ServerSoftwareParser::parse($serverVersion);

            if ($parsed instanceof SoftwareDetails) {
                $software[] = $parsed;
            }
        }

        if ($this->includeOperatingSystem) {
            $software[] = new SoftwareDetails(
                name: php_uname('s'),
                versionString: php_uname('r')
            );
        }

        foreach ($this->customVersions as $customVersion) {
            $software[] = new SoftwareDetails(
                name: $customVersion['name'],
                versionString: $customVersion['version']
            );
        }

        return new JsonResponse([
            'ok'      => true,
            'type'    => 'orchestra/external/endpoint',
            'payload' => [
                'healthy'  => true,
                'software' => $software,
            ],
        ]);
    }
}
