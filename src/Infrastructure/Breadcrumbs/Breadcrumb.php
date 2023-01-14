<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Infrastructure\Breadcrumbs;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Breadcrumb
{
    public readonly string $name;
    public readonly ?string $route;

    public function __construct(string $name, string $route = null)
    {
        $this->name = $name;
        $this->route = $route;
    }


}