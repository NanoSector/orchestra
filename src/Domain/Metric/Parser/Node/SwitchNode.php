<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Metric\Parser\Node;

use Closure;
use InvalidArgumentException;

readonly class SwitchNode extends AbstractParserControlStructure
{
    public const DEFAULT = '_default';

    public function __construct(
        protected Closure $reducer,
        protected array $cases
    ) {
    }

    public function parse(string|array $value): array
    {
        $reduced = call_user_func($this->reducer, $value);

        if (!is_string($reduced)) {
            throw new InvalidArgumentException('SwitchNode expects a callback that reduces the value to a string');
        }

        foreach ($this->cases as $case => $result) {
            if ($reduced === $case) {
                return $result;
            }
        }

        if (array_key_exists(self::DEFAULT, $this->cases)) {
            return $this->cases[self::DEFAULT];
        }

        return [];
    }
}