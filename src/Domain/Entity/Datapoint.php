<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orchestra\Domain\Metric\DatapointSpecialist;
use Orchestra\Domain\Repository\DatapointRepository;
use Orchestra\Infrastructure\Doctrine\Traits\TimestampedEntityTrait;

#[ORM\Entity(repositoryClass: DatapointRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Datapoint
{
    // Yes, this is timestamped!
    // Reason for this is that a datapoint can remain the same, and we don't want
    // to pollute the database with equal data points.
    use TimestampedEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'datapoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Metric $metric = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetric(): ?Metric
    {
        return $this->metric;
    }

    public function setMetric(?Metric $metric): self
    {
        $this->metric = $metric;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function toSpecialist(): DatapointSpecialist
    {
        return new DatapointSpecialist($this);
    }
}
