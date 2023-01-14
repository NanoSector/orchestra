<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Metric;

use Domain\Entity\Datapoint;
use Web\Helper\Badge;

interface MetricInterface
{
    public function getName(): string;

    public function getValue(): mixed;
}