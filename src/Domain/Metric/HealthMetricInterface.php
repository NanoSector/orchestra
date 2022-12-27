<?php
declare(strict_types=1);

namespace Domain\Metric;

interface HealthMetricInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getHealthAttributes(): array;
}