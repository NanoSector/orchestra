<?php

declare(strict_types = 1);

namespace Api\Controller\Metrics\V1;

use Api\Controller\AbstractApiController;
use Api\Support\ApiProblem;
use Domain\Entity\Metric;
use Domain\Entity\User;
use Domain\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MetricController extends AbstractApiController
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    #[Route('/api/v1/metrics/{id}/pin', name: 'api_metrics_v1_metric_pin', methods: ["POST"])]
    public function pin(Metric $metric): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->problemResponse(ApiProblem::fromHttpCode(Response::HTTP_UNAUTHORIZED));
        }

        $pinnedMetrics = $user->decoratePinnedMetrics();

        if ($pinnedMetrics->hasPinnedMetric($metric)) {
            $this->verbose()->add('Metric was already pinned; no action taken');

            return $this->okResponse();
        }

        $pinnedMetrics->pinMetric($metric);
        $this->userRepository->save($user, true);

        $this->verbose()->add('Metric has been pinned and saved');

        return $this->okResponse();
    }

    #[Route('/api/v1/metrics/{id}/unpin', name: 'api_metrics_v1_metric_unpin', methods: ["POST"])]
    public function unpin(Metric $metric): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->problemResponse(ApiProblem::fromHttpCode(Response::HTTP_UNAUTHORIZED));
        }

        $pinnedMetrics = $user->decoratePinnedMetrics();

        if (!$pinnedMetrics->hasPinnedMetric($metric)) {
            $this->verbose()->add('Metric was not pinned; no action taken');

            return $this->okResponse();
        }

        $pinnedMetrics->unpinMetric($metric);
        $this->userRepository->save($user, true);

        $this->verbose()->add('Metric has been unpinned and saved');

        return $this->okResponse();
    }
}