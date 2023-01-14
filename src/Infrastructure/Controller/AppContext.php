<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Infrastructure\Controller;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AppContext
{
    public readonly string $appContext;

    public function __construct(string $appContext)
    {
        $this->appContext = $appContext;
    }
}