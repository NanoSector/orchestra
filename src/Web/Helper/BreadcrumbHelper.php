<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Web\Helper;

use Infrastructure\Breadcrumbs\BreadcrumbBag;
use Symfony\Component\HttpFoundation\Request;

class BreadcrumbHelper
{
    public static function request(Request $request): BreadcrumbBag
    {
        /** @var BreadcrumbBag $breadcrumbBag */
        $breadcrumbBag = $request->attributes->get('breadcrumbs');

        return $breadcrumbBag;
    }
}