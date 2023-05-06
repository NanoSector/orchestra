<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric;

use JsonException;
use Orchestra\Domain\Entity\Datapoint;

readonly class TextMetric implements MetricInterface
{
    public function __construct(
        protected string $product,
        protected string $value
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->product;
    }
}
