<?php

declare(strict_types = 1);

namespace Web\Controller;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Domain\Collection\MetricsPerProductCollection;
use Domain\Endpoint\Driver\DriverInterface;
use Domain\Entity\Application;
use Domain\Entity\Datapoint;
use Domain\Entity\Endpoint;
use Domain\Entity\Metric;
use Domain\Entity\User;
use Domain\Metric\MetricEnum;
use Domain\Repository\EndpointRepository;
use Domain\Repository\MetricRepository;
use Domain\Repository\UserRepository;
use Infrastructure\Breadcrumbs\BreadcrumbBag;
use Infrastructure\Breadcrumbs\BreadcrumbItem;
use Infrastructure\Controller\AppContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Web\Form\EndpointForm;
use Web\Helper\Flash;
use Web\ViewModel\MetricViewModel;

#[AppContext('app_management')]
class EndpointController extends AbstractController
{

    private EndpointRepository $endpointRepository;
    private MetricRepository $metricRepository;
    private UserRepository $userRepository;

    public function __construct(
        EndpointRepository $endpointRepository,
        MetricRepository $metricRepository,
        UserRepository $userRepository
    ) {
        $this->endpointRepository = $endpointRepository;
        $this->metricRepository = $metricRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/applications/{applicationId}/endpoints/create', name: 'web_endpoint_create', methods: ["GET", "POST"])]
    #[ParamConverter("application", options: ["id" => "applicationId"])]
    public function create(Application $application, Request $request): Response
    {
        /** @var BreadcrumbBag $breadcrumbBag */
        $breadcrumbBag = $request->attributes->get('breadcrumbs');
        $breadcrumbBag->add([
            'application' => new BreadcrumbItem(
                sprintf('Application %s', $application->getName()),
                $this->generateUrl('web_application_details', ['id' => $application->getId()])
            ),
            'endpoint'    => new BreadcrumbItem('Create endpoint', null, true),
        ]);

        $endpoint = new Endpoint();
        $endpoint->setApplication($application);

        $form = $this->createForm(EndpointForm::class, $endpoint);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $driverOptions = json_decode($form->get('driverOptions')->getData(), true, 512, JSON_THROW_ON_ERROR);

                if (!is_array($driverOptions)) {
                    $driverOptions = [$driverOptions];
                }
                $endpoint->setDriverOptions($driverOptions);
                $endpoint->setInterval(30);

                $this->endpointRepository->save($endpoint, true);
                $this->addFlash(Flash::OK, 'The endpoint has been created.');

                return $this->redirectToRoute(
                    'web_endpoint_details',
                    ['applicationId' => $application->getId(), 'id' => $endpoint->getId()]
                );
            } catch (\JsonException $e) {
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
    #[ParamConverter("application", options: ["id" => "applicationId"])]
    public function details(Application $application, Endpoint $endpoint, Request $request): Response
    {
        if (!$endpoint->belongsToApplication($application)) {
            throw $this->createNotFoundException();
        }

        /** @var BreadcrumbBag $breadcrumbBag */
        $breadcrumbBag = $request->attributes->get('breadcrumbs');
        $breadcrumbBag->add([
            'application' => new BreadcrumbItem(
                sprintf('Application %s', $application->getName()),
                $this->generateUrl('web_application_details', ['id' => $application->getId()])
            ),
            'endpoint'    => new BreadcrumbItem('Endpoint', null, true),
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
            'application' => $application,
            'endpoint' => $endpoint,
            'lastMetricsPerProduct' => $lastMetricsPerProduct,
        ]);
    }

    #[Route('/applications/{applicationId}/endpoints/{id}/test', name: 'web_endpoint_test', methods: ["GET"])]
    #[ParamConverter("application", options: ["id" => "applicationId"])]
    public function test(Application $application, Endpoint $endpoint, KernelInterface $kernel): Response
    {
        // TODO: Refactor this into actual classes
        /** @var DriverInterface $driver */
        $driver = $kernel->getContainer()->get($endpoint->getDriver()->value);

        $response = $driver->fetch($endpoint);

        $endpoint->setLastSuccessfulResponse(Carbon::now());
        $this->endpointRepository->save($endpoint, true);

        foreach ($response->getMetrics() as $metric) {
            $metricEntity = $endpoint->getMetrics()->findFirst(
                fn($key, Metric $m) => $m->getProduct() === $metric->getName()
                    && $m->getDiscriminator()->value === get_class($metric)
            );

            if (!$metricEntity instanceof Metric) {
                $metricEntity = new Metric();
                $metricEntity->setEndpoint($endpoint);
                $metricEntity->setProduct($metric->getName());
                $metricEntity->setDiscriminator(MetricEnum::from(get_class($metric)));

                $this->metricRepository->save($metricEntity);
            }

            $lastDatapoint = $metricEntity->getLastDatapoint();
            $newValue = json_encode($metric->getValue(), JSON_THROW_ON_ERROR);

            if ($lastDatapoint instanceof Datapoint && $newValue === $lastDatapoint->getValue()) {
                $lastDatapoint->setUpdatedAt();

                $this->metricRepository->save($metricEntity, true);
                continue;
            }

            $datapoint = new Datapoint();
            $datapoint->setValue($newValue);
            $datapoint->setUpdatedAt();
            $metricEntity->addDatapoint($datapoint);

            $this->metricRepository->save($metricEntity, true);
        }

        $this->addFlash('success', 'The endpoint has been tested.');

        return $this->redirectToRoute(
            'web_endpoint_details',
            ['applicationId' => $application->getId(), 'id' => $endpoint->getId()]
        );
    }

    #[Route('/applications/{applicationId}/endpoints/{id}/update', name: 'web_endpoint_update', methods: [
        "GET",
        "POST"
    ])]
    #[ParamConverter("application", options: ["id" => "applicationId"])]
    public function update(Application $application, Endpoint $endpoint, Request $request): Response
    {
        /** @var BreadcrumbBag $breadcrumbBag */
        $breadcrumbBag = $request->attributes->get('breadcrumbs');
        $breadcrumbBag->add([
            'application' => new BreadcrumbItem(
                sprintf('Application %s', $application->getName()),
                $this->generateUrl('web_application_details', ['id' => $application->getId()])
            ),
            'endpoint'    => new BreadcrumbItem('Update endpoint', null, true),
        ]);

        $form = $this->createForm(EndpointForm::class, $endpoint);
        $form->get('driverOptions')->setData(json_encode($endpoint->getDriverOptions(), JSON_THROW_ON_ERROR));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $driverOptions = json_decode($form->get('driverOptions')->getData(), true, 512, JSON_THROW_ON_ERROR);

                if (!is_array($driverOptions)) {
                    $driverOptions = [$driverOptions];
                }
                $endpoint->setDriverOptions($driverOptions);
                $endpoint->setInterval(30);

                $this->endpointRepository->save($endpoint, true);
                $this->addFlash(Flash::OK, 'The endpoint has been updated.');

                return $this->redirectToRoute(
                    'web_endpoint_details',
                    ['applicationId' => $application->getId(), 'id' => $endpoint->getId()]
                );
            } catch (\JsonException $e) {
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
