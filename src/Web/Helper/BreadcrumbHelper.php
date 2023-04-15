<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Web\Helper;

use Orchestra\Web\Breadcrumb\BreadcrumbBag;
use Orchestra\Web\Breadcrumb\BreadcrumbListener;
use Orchestra\Web\Exception\BreadcrumbException;
use Symfony\Component\HttpFoundation\Request;

class BreadcrumbHelper
{
    /**
     * @throws BreadcrumbException
     */
    public static function request(Request $request): BreadcrumbBag
    {
        /** @var BreadcrumbBag $breadcrumbBag */
        $breadcrumbBag = $request->attributes->get('breadcrumbs');

        if (!$breadcrumbBag instanceof BreadcrumbBag) {
            throw new BreadcrumbException(
                sprintf('No breadcrumb bag was created for this request; was %s called?', BreadcrumbListener::class)
            );
        }

        return $breadcrumbBag;
    }
}