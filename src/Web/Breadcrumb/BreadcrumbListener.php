<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Web\Breadcrumb;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

readonly class BreadcrumbListener implements EventSubscriberInterface
{
    public function __construct(
        private RouterInterface $router
    ) {
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