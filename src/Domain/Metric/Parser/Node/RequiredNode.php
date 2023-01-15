<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Metric\Parser\Node;

use Domain\Exception\RequiredParserNodeNotFoundException;

readonly class RequiredNode extends AbstractParserControlStructure
{
    public function __construct(
        protected array $children
    ) {
    }

    /**
     * @throws RequiredParserNodeNotFoundException
     */
    public function handleNotFound(): void
    {
        throw new RequiredParserNodeNotFoundException();
    }

    public function parse(array|string $value): array
    {
        return $this->children;
    }
}