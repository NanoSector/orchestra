<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Web\Controller;

use Domain\Entity\Application;
use Domain\Entity\Endpoint;
use Domain\Entity\Metric;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Web\Breadcrumb\Breadcrumb;
use Web\Breadcrumb\BreadcrumbBuilder;
use Web\Helper\BreadcrumbHelper;
use Web\ViewModel\DatapointViewModel;

#[Breadcrumb('Applications', 'web_application_index')]
class MetricController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder
    ) {
    }

    #[Route('/applications/{applicationId}/endpoints/{endpointId}/metrics/{id}', name: 'web_metric_details', methods: ["GET"])]
    public function details(
        #[MapEntity(id: 'applicationId')] Application $application,
        #[MapEntity(id: 'endpointId')] Endpoint $endpoint,
        Metric $metric,
        Request $request
    ): Response {
        if (!$metric->belongsToEndpoint($endpoint) || !$endpoint->belongsToApplication($application)) {
            throw $this->createNotFoundException();
        }

        BreadcrumbHelper::request($request)->add([
            'application' => $this->breadcrumbBuilder->application($application),
            'endpoint'    => $this->breadcrumbBuilder->endpoint($endpoint),
            'current'     => $this->breadcrumbBuilder->text(sprintf('Metric %s', $metric->getProduct()), true),
        ]);

        /** @var DatapointViewModel[] $datapointViewModels */
        $datapointViewModels = iterator_to_array(DatapointViewModel::fromAllDatapointsInMetric($metric));

        return $this->render('metric/details.html.twig', [
            'controller_name'     => 'MetricController',
            'metric'              => $metric,
            'datapointViewModels' => $datapointViewModels,
        ]);
    }
}
