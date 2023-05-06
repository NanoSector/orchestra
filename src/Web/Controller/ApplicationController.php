<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Orchestra\Domain\Collection\MetricsPerProductCollection;
use Orchestra\Domain\Entity\Application;
use Orchestra\Domain\Entity\Metric;
use Orchestra\Domain\Entity\MetricPin;
use Orchestra\Domain\Entity\User;
use Orchestra\Domain\Repository\ApplicationRepositoryInterface;
use Orchestra\Infrastructure\Controller\AppContext;
use Orchestra\Web\Breadcrumb\Breadcrumb;
use Orchestra\Web\Breadcrumb\BreadcrumbBuilder;
use Orchestra\Web\Exception\BreadcrumbBuilderException;
use Orchestra\Web\Exception\BreadcrumbException;
use Orchestra\Web\Form\ApplicationForm;
use Orchestra\Web\Helper\BreadcrumbHelper;
use Orchestra\Web\Helper\Flash;
use Orchestra\Web\ViewModel\MetricViewModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AppContext('app_management')]
#[Breadcrumb('Applications', 'web_application_index')]
class ApplicationController extends AbstractController
{
    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly BreadcrumbBuilder $breadcrumbBuilder
    ) {
    }

    #[Route('/applications/create', name: 'web_application_create', methods: ["GET", "POST"])]
    #[Breadcrumb('Create application')]
    public function create(Request $request): Response
    {
        $application = new Application();

        $form = $this->createForm(ApplicationForm::class, $application);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->applicationRepository->save($application);

            $this->addFlash(Flash::OK, 'The application has been created.');

            return $this->redirectToRoute('web_application_update', ['id' => $application->getId()]);
        }

        return $this->render('applications/create.html.twig', [
            'application' => $application,
            'form'        => $form,
        ]);
    }

    #[Route('/applications/{id}/delete', name: 'web_application_delete', methods: ["POST"])]
    public function delete(Application $application): Response
    {
        $this->applicationRepository->delete($application);

        $this->addFlash(Flash::OK, 'The application has been deleted.');

        return $this->redirectToRoute('web_application_index');
    }

    /**
     * @throws BreadcrumbException
     * @throws BreadcrumbBuilderException
     */
    #[Route('/applications/{id}', name: 'web_application_details', methods: ["GET"])]
    public function details(Request $request, Application $application): Response
    {
        BreadcrumbHelper::request($request)->add([
            'application' => $this->breadcrumbBuilder->application($application)
        ]);

        $pinnedMetricsPerProduct = new ArrayCollection();

        $user = $this->getUser();

        if ($user instanceof User) {
            $userPinnedMetrics = $user->decoratePinnedMetrics();
            $pinnedMetrics = $userPinnedMetrics->findPinsByApplication($application);

            $metrics = $pinnedMetrics->map(fn(MetricPin $p) => $p->getMetric());
            $perProductCollection = MetricsPerProductCollection::fromMetricCollection($metrics);

            $pinnedMetricsPerProduct = $perProductCollection->map(
                static fn(ArrayCollection $metrics) => $metrics->map(
                    fn(Metric $m) => MetricViewModel::fromLastDatapointInMetric($m)
                                                    ->setPinned($userPinnedMetrics->hasPinnedMetric($m))
                )
            );
        }

        return $this->render('applications/details.html.twig', [
            'application'             => $application,
            'pinnedMetricsPerProduct' => $pinnedMetricsPerProduct,
        ]);
    }

    #[Route('/applications', name: 'web_application_index')]
    public function index(): Response
    {
        return $this->render('applications/index.html.twig', [
            'applications' => $this->applicationRepository->findAll(),
        ]);
    }

    /**
     * @throws BreadcrumbException
     * @throws BreadcrumbBuilderException
     */
    #[Route('/applications/{id}/update', name: 'web_application_update', methods: ["GET", "POST"])]
    public function update(Application $application, Request $request): Response
    {
        BreadcrumbHelper::request($request)->add([
            'application' => $this->breadcrumbBuilder->application($application),
            'current'     => $this->breadcrumbBuilder->text('Update application', true),
        ]);

        $form = $this->createForm(ApplicationForm::class, $application);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->applicationRepository->save($application);

            $this->addFlash(Flash::OK, 'The application has been updated.');

            return $this->redirectToRoute('web_application_update', ['id' => $application->getId()]);
        }

        return $this->render('applications/update.html.twig', [
            'application' => $application,
            'form'        => $form,
        ]);
    }
}
