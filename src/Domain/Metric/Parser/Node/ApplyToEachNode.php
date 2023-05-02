<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric\Parser\Node;

use Closure;

readonly class ApplyToEachNode extends AbstractParserControlStructure
{
    public function __construct(
        protected Closure $consumer
    ) {
    }

    public function parse(array|string $value): array
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        foreach ($value as $key => $item) {
            $value[$key] = call_user_func($this->consumer, $item, $key);
        }

        return $value;
    }
}
