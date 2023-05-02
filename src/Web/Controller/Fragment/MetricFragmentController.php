<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\Controller\Fragment;

use Orchestra\Domain\Entity\Metric;
use Orchestra\Domain\Entity\User;
use Orchestra\Web\ViewModel\MetricViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MetricFragmentController extends AbstractController
{
    #[Route('/fragments/metric/{id}/actions', name: 'web_fragment_metric_actions')]
    public function actions(Metric $metric): Response
    {
        $user = $this->getUser();

        $viewModel = MetricViewModel::fromLastDatapointInMetric($metric);

        if ($user instanceof User) {
            $viewModel->setPinned($user->decoratePinnedMetrics()->hasPinnedMetric($metric));
        }

        return $this->render('fragments/metric/metric-actions.html.twig', [
            'metric' => $viewModel,
        ]);
    }
}
