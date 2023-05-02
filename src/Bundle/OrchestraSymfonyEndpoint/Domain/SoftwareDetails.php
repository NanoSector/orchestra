<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Bundle\Endpoint\Domain;

use JsonSerializable;

readonly class SoftwareDetails implements JsonSerializable
{
    public function __construct(
        public string $name,
        public string $versionString,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'name'    => $this->name,
            'version' => $this->versionString,
        ];
    }
}
