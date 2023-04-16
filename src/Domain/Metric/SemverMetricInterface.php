<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Metric;

interface SemverMetricInterface extends MetricInterface
{
    public function getValue(): string;

    public function isEqualTo(SemverMetricInterface $other): bool;

    public function isGreaterThan(SemverMetricInterface $other): bool;

    public function isLessThan(SemverMetricInterface $other): bool;
}