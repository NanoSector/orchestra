<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Infrastructure\Breadcrumbs;

readonly class BreadcrumbItem
{
    public ?string $url;

    public function __construct(
        public string $name,
        ?string $url,
        public bool $active = false
    ) {
        if ($active) {
            $url = null;
        }

        $this->url = $url;
    }
}