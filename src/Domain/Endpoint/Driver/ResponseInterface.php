<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Endpoint\Driver;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Metric\MetricInterface;

interface ResponseInterface
{
    /**
     * @return ArrayCollection<MetricInterface>
     */
    public function getMetrics(): ArrayCollection;
}