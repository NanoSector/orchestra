<?php
declare(strict_types=1);

namespace Domain\Metric;

enum MetricEnum: string
{
    case Health = HealthMetric::class;
    case Invalid = InvalidMetric::class;
    case Text = TextMetric::class;
    case Version = VersionMetric::class;
}