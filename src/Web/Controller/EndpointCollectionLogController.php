<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Web\Controller;

use Orchestra\Domain\Entity\Application;
use Orchestra\Domain\Entity\Endpoint;
use Orchestra\Domain\Entity\EndpointCollectionLog;
use Orchestra\Infrastructure\Controller\AppContext;
use Orchestra\Web\Breadcrumb\Breadcrumb;
use Orchestra\Web\Breadcrumb\BreadcrumbBuilder;
use Orchestra\Web\Helper\BreadcrumbHelper;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
