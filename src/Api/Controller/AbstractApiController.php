<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Api\Controller;

use Orchestra\Api\Support\ApiProblem;
use Orchestra\Api\Support\LinkBag;
use Orchestra\Api\Support\VerboseMessageBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Service\Attribute\Required;

class AbstractApiController extends AbstractController
{
    private readonly VerboseMessageBag $verbose;

    private readonly LinkBag $links;

    private string $environment;

    public function __construct()
    {
        $this->verbose = new VerboseMessageBag();
        $this->links = new LinkBag();
    }

    public function okResponse(): JsonResponse
    {
        return $this->json($this->wrapPayload([]));
    }

    protected function wrapPayload(mixed $payload, int $status = 200): array
    {
        $response = [
            'ok'      => $status >= 200 && $status < 400,
            'payload' => $payload,
        ];

        if (!empty($this->links)) {
            $response['_links'] = $this->links->getAll();
        }

        if ($this->environment === 'dev') {
            $response['_handler'] = static::class;
            $response['_verbose'] = $this->verbose()->getAll();
        }

        return $response;
    }

    protected function verbose(): VerboseMessageBag
    {
        return $this->verbose;
    }

    public function problemResponse(ApiProblem $apiProblem): JsonResponse
    {
        $response = $this->json($apiProblem->jsonSerialize());

        $response->setContent('application/problem+json');

        return $response;
    }

    #[Required]
    public function setEnvironment(#[Autowire(value: '%kernel.environment%')] string $environment): void
    {
        $this->verbose->add(sprintf('Kernel environment detected as %s', $environment));
        $this->environment = $environment;
    }

    protected function links(): LinkBag
    {
        return $this->links;
    }
}
