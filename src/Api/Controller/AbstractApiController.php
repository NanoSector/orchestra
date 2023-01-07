<?php

declare(strict_types = 1);

namespace Api\Controller;

use Api\Support\ApiProblem;
use Api\Support\LinkBag;
use Api\Support\VerboseMessageBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AbstractApiController extends AbstractController
{
    private VerboseMessageBag $verbose;

    private LinkBag $links;

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

        if ($this->container->getParameter('kernel.environment') === 'dev') {
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

    protected function links(): LinkBag
    {
        return $this->links;
    }
}