<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Endpoint\Driver;

use Doctrine\Common\Collections\ArrayCollection;

readonly abstract class AbstractDriverResponse implements DriverResponseInterface
{

    public function __construct(private ArrayCollection $metrics)
    {
    }

    public function getMetrics(): ArrayCollection
    {
        return $this->metrics;
    }

}