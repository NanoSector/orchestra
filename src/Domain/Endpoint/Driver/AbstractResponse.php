<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Endpoint\Driver;

use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractResponse implements ResponseInterface
{
    private ArrayCollection $metrics;

    public function __construct(ArrayCollection $metrics)
    {
        $this->metrics = $metrics;
    }

    public function getMetrics(): ArrayCollection
    {
        return $this->metrics;
    }

}