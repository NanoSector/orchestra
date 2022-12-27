<?php
declare(strict_types=1);

namespace Domain\Endpoint\Driver;

use Domain\Metric\HealthMetricInterface;

interface ResponseWithHealthCheckInterface
{
    public function getHealthMetric(): ?HealthMetricInterface;
}