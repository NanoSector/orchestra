<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Infrastructure\Controller;

use Infrastructure\Exception\DuplicateAppContextException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class AppContextAwareControllerListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                ['injectAppContext', 0]
            ]
        ];
    }

    /**
     * @throws DuplicateAppContextException
     */
    public function injectAppContext(ControllerEvent $event): void
    {
        $attributes = $event->getAttributes();

        if (!array_key_exists(AppContext::class, $attributes)) {
            return;
        }

        foreach ($attributes[AppContext::class] as $appContext) {
            if ($event->getRequest()->attributes->has('appContext')) {
                throw new DuplicateAppContextException();
            }

            if ($appContext instanceof AppContext) {
                $event->getRequest()->attributes->set('appContext', $appContext->appContext);
            }
        }
    }
}