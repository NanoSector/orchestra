<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric\Parser\Node;

use Closure;
use Orchestra\Domain\Metric\HealthMetric;
use Orchestra\Domain\Metric\MetricInterface;

readonly class HealthCheckAttributesNode implements ParserNodeInterface
{
    public function __construct(
        protected string $product,
        protected Closure $condition
    ) {
    }

    public function parse(array|string $value): MetricInterface
    {
        $result = call_user_func($this->condition, $value);

        $attributes = !is_array($value) ? [$value] : $value;

        return new HealthMetric($this->product, (bool)$result, $attributes);
    }
}
