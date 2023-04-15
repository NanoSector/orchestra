<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Metric\Parser\Specialized;

use Orchestra\Domain\Metric\Parser\Node\RegexVersionNode;

readonly class CaddyVersionNode extends RegexVersionNode
{
    public function __construct()
    {
        parent::__construct('Caddy', '/^Caddy\/v(.+)$/');
    }

}