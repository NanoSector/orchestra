<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Endpoint\Driver;

use Domain\Metric\HealthMetricInterface;
use Domain\Metric\MetricInterface;

trait DriverResponseWithHealthCheckTrait
{
    public function getHealthMetric(): ?HealthMetricInterface
    {
        return $this->getMetrics()->findFirst(
            static fn($key, MetricInterface $m) => $m instanceof HealthMetricInterface
        );
    }
}