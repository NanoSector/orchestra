<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

use Domain\Exception\RequiredParserNodeNotFoundException;

class RequiredNode extends AbstractParserControlStructure
{

    private array $children;

    public function __construct(array $children)
    {
        $this->children = $children;
    }

    public function parse(array|string $value): array
    {
        return $this->children;
    }

    /**
     * @throws RequiredParserNodeNotFoundException
     */
    public function handleNotFound(): void
    {
        throw new RequiredParserNodeNotFoundException();
    }
}