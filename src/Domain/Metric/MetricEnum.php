<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Metric;

enum MetricEnum: string
{
    case Health = HealthMetric::class;
    case Invalid = InvalidMetric::class;
    case Text = TextMetric::class;
    case Version = VersionMetric::class;
}