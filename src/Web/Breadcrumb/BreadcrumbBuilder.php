<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Web\Breadcrumb;

use Domain\Entity\Application;
use Domain\Entity\Endpoint;
use Domain\Entity\Group;
use Infrastructure\Breadcrumbs\BreadcrumbItem;
use Symfony\Component\Routing\RouterInterface;

readonly class BreadcrumbBuilder
{
    public function __construct(
        private RouterInterface $router
    ) {
    }

    public function application(Application $application, bool $active = false): BreadcrumbItem
    {
        return new BreadcrumbItem(
            $application->getName(),
            $this->router->generate(
                'web_application_details',
                ['id' => $application->getId()],
            ),
            $active
        );
    }

    public function endpoint(Endpoint $endpoint, bool $active = false): BreadcrumbItem
    {
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