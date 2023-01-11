<?php

declare(strict_types = 1);

namespace Web\Controller\Fragment;

use Domain\Entity\Metric;
use Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Web\ViewModel\MetricViewModel;

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