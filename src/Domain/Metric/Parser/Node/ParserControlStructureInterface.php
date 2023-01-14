<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

interface ParserControlStructureInterface
{
    public function parse(string|array $value): array;

    public function handleNotFound(): void;
}