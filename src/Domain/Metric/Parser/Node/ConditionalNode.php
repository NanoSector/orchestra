<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric\Parser\Node;

use Closure;

readonly class ConditionalNode extends AbstractParserControlStructure
{
    public function __construct(
        protected Closure $condition,
        protected array $ifTrue,
        protected array $ifFalse = []
    ) {
    }

    public function parse(string|array $value): array
    {
        $result = call_user_func($this->condition, $value);

        return $result ? $this->ifTrue : $this->ifFalse;
    }
}
