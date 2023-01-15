<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

readonly abstract class AbstractParserControlStructure implements ParserControlStructureInterface
{
    public function handleNotFound(): void
    {
        // No-op, most structures do not need to handle this
    }

}