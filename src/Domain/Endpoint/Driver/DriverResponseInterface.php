<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint\Driver;

use Doctrine\Common\Collections\ArrayCollection;
use Orchestra\Domain\Metric\MetricInterface;

interface DriverResponseInterface
{
    /**
     * @return ArrayCollection<MetricInterface>
     */
    public function getMetrics(): ArrayCollection;
}
