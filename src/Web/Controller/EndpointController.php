<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use JsonException;
use Orchestra\Domain\Collection\MetricsPerProductCollection;
use Orchestra\Domain\Endpoint\EndpointClient;
use Orchestra\Domain\Endpoint\EndpointDriverResponseMapper;
use Orchestra\Domain\Entity\Application;
use Orchestra\Domain\Entity\Endpoint;
use Orchestra\Domain\Entity\Metric;
use Orchestra\Domain\Entity\User;
use Orchestra\Domain\Repository\EndpointRepository;
use Orchestra\Domain\Repository\EndpointRepositoryInterface;
use Orchestra\Infrastructure\Controller\AppContext;
use Orchestra\Web\Breadcrumb\Breadcrumb;
use Orchestra\Web\Breadcrumb\BreadcrumbBuilder;
use Orchestra\Web\Form\EndpointForm;
use Orchestra\Web\Helper\BreadcrumbHelper;
use Orchestra\Web\Helper\Flash;
use Orchestra\Web\ViewModel\MetricViewModel;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AppContext('app_management')]
#[Breadcrumb('Applications', 'web_application_index')]
class EndpointController extends AbstractController
{
    public function __construct(
        private readonly EndpointRepositoryInterface $endpointRepository,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
    ) {
    }

    #[Route('/applications/{applicationId}/endpoints/create', name: 'web_endpoint_create', methods: ["GET", "POST"])]
    public function create(
        #[MapEntity(id: 'applicationId')] Application $application,
        Request $request
    ): Response {
        BreadcrumbHelper::request($request)->add([
            'application' => $this->breadcrumbBuilder->application($application),
            'current'     => $this->breadcrumbBuilder->text('Create endpoint', true),
        ]);

        $endpoint = new Endpoint();
        $endpoint->setApplication($application);

        $form = $this->createForm(EndpointForm::class, $endpoint);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $driverOptions = json_decode((string)$form->get('driverOptions')->getData(), true, 512, JSON_THROW_ON_ERROR);

                if (!is_array($driverOptions)) {
                    $driverOptions = [$driverOptions];
                }
                $endpoint->setDriverOptions($driverOptions);
                $endpoint->setInterval(30);

                $this->endpointRepository->save($endpoint);
                $this->addFlash(Flash::OK, 'The endpoint has been created.');

                return $this->redirectToRoute(
                    'web_endpoint_details',
                    ['applicationId' => $application->getId(), 'id' => $endpoint->getId()]
                );
            } catch (JsonException) {
                // no-op, re-enter the form
                $form->addError(
                    new FormError(
                        'The driver options must be valid JSON'
                    )
                );
            }
        }

        return $this->render('endpoints/create.html.twig', [
            'application' => $application,
            'endpoint'    => $endpoint,
            'form'        => $form,
        ]);
    }

    #[Route('/endpoint/{id}/delete', name: 'web_endpoint_delete', methods: ["POST"])]
    public function delete(Application $application): Response
    {
        $this->applicationRepository->remove($application, true);

        $this->addFlash('success', 'The endpoint has been deleted.');

        return $this->redirectToRoute('web_endpoint_index');
    }

    #[Route('/applications/{applicationId}/endpoints/{id}', name: 'web_endpoint_details', methods: ["GET"])]
    public function details(
        #[MapEntity(id: 'applicationId')] Application $application,
        Endpoint $endpoint,
        Request $request
    ): Response {
        if (!$endpoint->belongsToApplication($application)) {
            throw $this->createNotFoundException();
        }

        BreadcrumbHelper::request($request)->add([
            'application' => $this->breadcrumbBuilder->application($application),
            'endpoint'    => $this->breadcrumbBuilder->endpoint($endpoint, true),
        ]);

        $metricsPerProduct = MetricsPerProductCollection::fromMetricCollection($endpoint->getMetrics());

        $user = $this->getUser();

        /** @var ArrayCollection<string, ArrayCollection<MetricViewModel>> $lastMetricsPerProduct */
        $lastMetricsPerProduct = $metricsPerProduct->map(
            static fn(ArrayCollection $metrics) => $metrics->map(
                fn(Metric $m) => MetricViewModel::fromLastDatapointInMetric($m)
                                                ->setPinned(
                                                    $user instanceof User
                                                        ? $user->decoratePinnedMetrics()->hasPinnedMetric($m)
                                                        : false
                                                )
            )
        );

        return $this->render('endpoints/details.html.twig', [
            'application'           => $application,
            'endpoint'              => $endpoint,
            'lastMetricsPerProduct' => $lastMetricsPerProduct,
        ]);
    }

    #[Route('/applications/{applicationId}/endpoints/{id}/test', name: 'web_endpoint_test', methods: ["GET"])]
    public function test(
        #[MapEntity(id: 'applicationId')] Application $application,
        Endpoint $endpoint,
        EndpointClient $endpointClient,
        EndpointDriverResponseMapper $mapper
    ): Response {
        $response = $endpointClient->fetch($endpoint);

        $log = $mapper->createCollectionLog($endpoint, $response);
        $endpoint->addCollectionLog($log);

        $endpoint = $mapper->map($endpoint, $response);
        $endpoint->touchLastSuccessfulResponse();

        $this->endpointRepository->save($endpoint);

        $this->addFlash(Flash::OK, 'The endpoint has been tested.');

        return $this->redirectToRoute(
            'web_endpoint_details',
            ['applicationId' => $application->getId(), 'id' => $endpoint->getId()]
        );
    }

    #[Route('/applications/{applicationId}/endpoints/{id}/update', name: 'web_endpoint_update', methods: [
        "GET",
        "POST",
    ])]
    public function update(
        #[MapEntity(id: 'applicationId')] Application $application,
        Endpoint $endpoint,
        Request $request
    ): Response {
        if (!$endpoint->belongsToApplication($application)) {
            throw $this->createNotFoundException();
        }

        BreadcrumbHelper::request($request)->add([
            'application' => $this->breadcrumbBuilder->application($application),
            'endpoint'    => $this->breadcrumbBuilder->endpoint($endpoint),
            'current'     => $this->breadcrumbBuilder->text('Update endpoint', true),
        ]);

        $form = $this->createForm(EndpointForm::class, $endpoint);
        $form->get('driverOptions')->setData(json_encode($endpoint->getDriverOptions(), JSON_THROW_ON_ERROR));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $driverOptions = json_decode((string)$form->get('driverOptions')->getData(), true, 512, JSON_THROW_ON_ERROR);

                if (!is_array($driverOptions)) {
                    $driverOptions = [$driverOptions];
                }
                $endpoint->setDriverOptions($driverOptions);
                $endpoint->setInterval(30);

                $this->endpointRepository->save($endpoint);
                $this->addFlash(Flash::OK, 'The endpoint has been updated.');

                return $this->redirectToRoute(
                    'web_endpoint_details',
                    ['applicationId' => $application->getId(), 'id' => $endpoint->getId()]
                );
            } catch (JsonException) {
                // no-op, re-enter the form
                $form->addError(
                    new FormError(
                        'The driver options must be valid JSON'
                    )
                );
            }
        }

        return $this->render('endpoints/update.html.twig', [
            'application' => $application,
            'endpoint'    => $endpoint,
            'form'        => $form,
        ]);
    }
}
