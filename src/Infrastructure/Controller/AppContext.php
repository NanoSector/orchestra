<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Infrastructure\Controller;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class AppContext
{
    public function __construct(
        public string $appContext
    ) {
    }
}