<?php

declare(strict_types=1);

namespace Web\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Entity\Application;
use Domain\Entity\Datapoint;
use Domain\Entity\Endpoint;
use Domain\Entity\User;
use Domain\Repository\ApplicationRepository;
use Infrastructure\Breadcrumbs\Breadcrumb;
use Infrastructure\Controller\AppContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Web\Form\ApplicationForm;
use Web\Helper\Flash;
use Web\ViewModel\MetricViewModel;

#[AppContext('app_management')]
#[Breadcrumb('Applications', 'web_application_index')]
class ApplicationController extends AbstractController
{

    private ApplicationRepository $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    #[Route('/applications', name: 'web_application_index')]
    public function index(): Response
    {
        return $this->render('applications/index.html.twig', [
            'applications' => $this->applicationRepository->findAll()
        ]);
    }

    #[Route('/applications/create', name: 'web_application_create', methods: ["GET", "POST"])]
    #[Breadcrumb('Create application')]
    public function create(Request $request): Response
    {
        $application = new Application();

        $form = $this->createForm(ApplicationForm::class, $application);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->applicationRepository->save($application, true);

            $this->addFlash(Flash::OK, 'The application has been created.');

            return $this->redirectToRoute('web_application_update', ['id' => $application->getId()]);
        }

        return $this->render('applications/create.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }

    #[Route('/applications/{id}', name: 'web_application_details', methods: ["GET"])]
    #[Breadcrumb('Application overview')]
    public function details(Application $application): Response
    {
        /** @var ArrayCollection<string, ArrayCollection<MetricViewModel>> $pinnedMetricsPerProduct */
        $pinnedMetricsPerProduct = new ArrayCollection();

        $user = $this->getUser();

        if ($user instanceof User) {
            foreach ($user->getPinnedMetrics() as $pinnedMetric) {
                $metric = $pinnedMetric->getMetric();

                if (!$metric->getEndpoint() instanceof Endpoint) {
                    continue;
                }

                if (!$metric->getEndpoint()->belongsToApplication($application)) {
                    continue;
                }

                $lastDatapoint = $metric->getLastDatapoint();

                if (!$lastDatapoint instanceof Datapoint) {
                    continue;
                }

                $product = $metric->getProduct() ?? 'N/A';

                if (!$pinnedMetricsPerProduct->containsKey($product)) {
                    $pinnedMetricsPerProduct->set($product, new ArrayCollection());
                }

                $metricObject = $lastDatapoint->toSpecialist()->makeMetricObject();
                $pinnedMetricsPerProduct->get($product)->add(new MetricViewModel($metric, $metricObject));
            }
        }

        return $this->render('applications/details.html.twig', [
            'application' => $application,
            'pinnedMetricsPerProduct' => $pinnedMetricsPerProduct,
        ]);
    }

    #[Route('/applications/{id}/update', name: 'web_application_update', methods: ["GET", "POST"])]
    #[Breadcrumb('Update application')]
    public function update(Application $application, Request $request): Response
    {
        $form = $this->createForm(ApplicationForm::class, $application);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->applicationRepository->save($application, true);

            $this->addFlash('success', 'The application has been updated.');

            return $this->redirectToRoute('web_application_update', ['id' => $application->getId()]);
        }

        return $this->render('applications/update.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }

    #[Route('/applications/{id}/delete', name: 'web_application_delete', methods: ["POST"])]
    public function delete(Application $application): Response
    {
        $this->applicationRepository->remove($application, true);

        $this->addFlash('success', 'The application has been deleted.');

        return $this->redirectToRoute('web_application_index');
    }
}
