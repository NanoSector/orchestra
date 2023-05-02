<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric\Parser\Node;

interface ParserControlStructureInterface
{
    public function handleNotFound(): void;

    public function parse(string|array $value): array;
}
