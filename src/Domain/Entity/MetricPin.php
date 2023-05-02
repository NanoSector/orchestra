<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
readonly class MetricPin
{
    public function __construct(
        #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Metric::class)]
        private Metric $metric,

        #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pinnedMetrics')]
        private User $user
    ) {
    }


    public function getMetric(): Metric
    {
        return $this->metric;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
