<?php
declare(strict_types=1);

namespace Domain\Endpoint\Driver;

use Domain\Metric\HealthMetricInterface;
use Domain\Metric\MetricInterface;

trait ResponseWithHealthCheckTrait
{
    public function getHealthMetric(): ?HealthMetricInterface
    {
        return $this->getMetrics()->findFirst(
            static fn($key, MetricInterface $m) => $m instanceof HealthMetricInterface
        );
    }
}