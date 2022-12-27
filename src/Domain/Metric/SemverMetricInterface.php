<?php

declare(strict_types=1);

namespace Domain\Metric;

interface SemverMetricInterface extends MetricInterface
{
    public function getValue(): string;

    public function isEqualTo(SemverMetricInterface $other): bool;

    public function isGreaterThan(SemverMetricInterface $other): bool;

    public function isLessThan(SemverMetricInterface $other): bool;
}