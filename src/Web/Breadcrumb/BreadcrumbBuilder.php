<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Web\Breadcrumb;

use Orchestra\Domain\Entity\Application;
use Orchestra\Domain\Entity\Endpoint;
use Orchestra\Web\Exception\BreadcrumbBuilderException;
use Symfony\Component\Routing\RouterInterface;

readonly class BreadcrumbBuilder
{
    public function __construct(
        private RouterInterface $router
    ) {
    }

    /**
     * @throws BreadcrumbBuilderException
     */
    public function application(Application $application, bool $active = false): BreadcrumbItem
    {
        if (!$application->getId()) {
            throw new BreadcrumbBuilderException(
                'Building an Endpoint breadcrumb item requires that both the Endpoint and its Application have an ID'
            );
        }

        return new BreadcrumbItem(
            $application->getName(),
            $this->router->generate(
                'web_application_details',
                ['id' => $application->getId()],
            ),
            $active
        );
    }

    /**
     * @throws BreadcrumbBuilderException
     */
    public function endpoint(Endpoint $endpoint, bool $active = false): BreadcrumbItem
    {
        if (!$endpoint->getApplication() instanceof Application) {
            throw new BreadcrumbBuilderException(
                'Cannot build an Endpoint breadcrumb item without an associated Application'
            );
        }

        if (!$endpoint->getId() || !$endpoint->getApplication()->getId()) {
            throw new BreadcrumbBuilderException(
                'Building an Endpoint breadcrumb item requires that both the Endpoint and its Application have an ID'
            );
        }

        return new BreadcrumbItem(
            sprintf('Endpoint %s', $endpoint->getName()),
            $this->router->generate(
                'web_endpoint_details',
                [
                    'applicationId' => $endpoint->getApplication()->getId(),
                    'id'            => $endpoint->getId()
                ],
            ),
            $active
        );
    }

    public function text(string $text, bool $active = false): BreadcrumbItem
    {
        return new BreadcrumbItem($text, null, $active);
    }
}