<?php

declare(strict_types=1);

namespace Infrastructure\Controller;

use Infrastructure\Breadcrumbs\Breadcrumb;
use Infrastructure\Breadcrumbs\BreadcrumbBag;
use Infrastructure\Breadcrumbs\BreadcrumbItem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class BreadcrumbListener implements EventSubscriberInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => [
                ['createBreadcrumbBag', 0]
            ]
        ];
    }

    public function createBreadcrumbBag(ControllerArgumentsEvent $event): void
    {
        $attributes = $event->getAttributes();

        $bag = new BreadcrumbBag();
        $event->getRequest()->attributes->set('breadcrumbs', $bag);

        $this->resolveBreadcrumbs($bag, $attributes);
    }

    private function resolveBreadcrumbs(BreadcrumbBag $bag, array $attributes): void
    {
        if (!array_key_exists(Breadcrumb::class, $attributes)) {
            return;
        }
        $breadcrumbs = $attributes[Breadcrumb::class];

        $activeItem = end($breadcrumbs);
        foreach ($attributes[Breadcrumb::class] as $breadcrumb) {
            if (!$breadcrumb instanceof Breadcrumb) {
                continue;
            }

            $url = is_null($breadcrumb->route) ? null : $this->router->generate($breadcrumb->route);

            $active = $breadcrumb === $activeItem;
            $bag->set($breadcrumb->name, new BreadcrumbItem($breadcrumb->name, $url, $active));
        }
    }
}