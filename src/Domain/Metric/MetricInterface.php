<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric;

interface MetricInterface
{
    public function getName(): string;

    public function getValue(): mixed;
}
