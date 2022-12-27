<?php

declare(strict_types=1);

namespace Infrastructure\Controller;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ModalAwareListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                ['injectModalContext', 0]
            ]
        ];
    }

    public function injectModalContext(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->headers->has('X-Modal-Request')) {
            $request->attributes->set('isModalRequest', true);
        }
    }
}