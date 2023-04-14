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
use Domain\Entity\EndpointCollectionLog;
use Infrastructure\Controller\AppContext;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Web\Breadcrumb\Breadcrumb;
use Web\Breadcrumb\BreadcrumbBuilder;
use Web\Helper\BreadcrumbHelper;

#[AppContext('app_management')]
#[Breadcrumb('Applications', 'web_application_index')]
class EndpointCollectionLogController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder
    ) {
    }

    #[Route('/applications/{applicationId}/endpoints/{endpointId}/log/{id}', name: 'web_endpoint_collection_log_details', methods: ["GET"])]
    public function details(
        #[MapEntity(id: 'applicationId')] Application $application,
        #[MapEntity(id: 'endpointId')] Endpoint $endpoint,
        EndpointCollectionLog $log,
        Request $request
    ): Response {
        if (!$endpoint->belongsToApplication($application) || !$log->belongsToEndpoint($endpoint)) {
            throw $this->createNotFoundException();
        }

        BreadcrumbHelper::request($request)->add([
            'application' => $this->breadcrumbBuilder->application($application),
            'endpoint'    => $this->breadcrumbBuilder->endpoint($endpoint),
            'current'     => $this->breadcrumbBuilder->text('Collection log', true),
        ]);

        return $this->render('endpoint_collection_logs/details.html.twig', [
            'application' => $application,
            'endpoint'    => $endpoint,
            'log'         => $log,
        ]);
    }
}
