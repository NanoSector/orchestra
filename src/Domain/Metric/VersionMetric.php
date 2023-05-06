<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric;

use Composer\Semver\Comparator;
use JsonException;
use Orchestra\Domain\Entity\Datapoint;

readonly class VersionMetric implements SemverMetricInterface
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

    public function isEqualTo(SemverMetricInterface $other): bool
    {
        return Comparator::equalTo($this->getValue(), $other->getValue());
    }

    public function isGreaterThan(SemverMetricInterface $other): bool
    {
        return Comparator::greaterThan($this->getValue(), $other->getValue());
    }

    public function isLessThan(SemverMetricInterface $other): bool
    {
        return Comparator::lessThan($this->getValue(), $other->getValue());
    }
}
