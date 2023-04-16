<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
readonly class MetricPin
{

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Metric::class)]
        #[ORM\Id]
        private Metric $metric,

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pinnedMetrics')]
        #[ORM\Id]
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