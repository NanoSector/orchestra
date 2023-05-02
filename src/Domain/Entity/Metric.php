<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Orchestra\Domain\Metric\MetricEnum;
use Orchestra\Domain\Repository\MetricRepository;
use Orchestra\Infrastructure\Doctrine\Traits\TimestampedEntityTrait;

#[ORM\Entity(repositoryClass: MetricRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Metric
{
    use TimestampedEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $product = null;

    #[ORM\OneToMany(mappedBy     : 'metric', targetEntity: Datapoint::class, cascade: [
        'persist',
        'remove',
    ], orphanRemoval: true)]
    private Collection $datapoints;

    #[ORM\ManyToOne(inversedBy: 'metrics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Endpoint $endpoint = null;

    #[ORM\Column(type: 'MetricEnumType', length: 255)]
    private ?MetricEnum $discriminator = null;

    public function __construct()
    {
        $this->datapoints = new ArrayCollection();
    }

    public function addDatapoint(Datapoint $datapoint): self
    {
        if (!$this->datapoints->contains($datapoint)) {
            $this->datapoints->add($datapoint);
            $datapoint->setMetric($this);
        }

        return $this;
    }

    public function belongsToEndpoint(Endpoint $endpoint): bool
    {
        if (!$this->endpoint instanceof Endpoint) {
            return false;
        }

        return $this->endpoint->getId() === $endpoint->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Datapoint>
     */
    public function getDatapoints(): Collection
    {
        return $this->datapoints;
    }

    public function getDiscriminator(): ?MetricEnum
    {
        return $this->discriminator;
    }

    public function setDiscriminator(MetricEnum $discriminator): self
    {
        $this->discriminator = $discriminator;

        return $this;
    }

    public function getEndpoint(): ?Endpoint
    {
        return $this->endpoint;
    }

    public function setEndpoint(?Endpoint $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getLastDatapoint(): ?Datapoint
    {
        $datapoint = $this->datapoints->last();

        return $datapoint instanceof Datapoint ? $datapoint : null;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function removeDatapoint(Datapoint $datapoint): self
    {
        if ($this->datapoints->removeElement($datapoint)) {
            // set the owning side to null (unless already changed)
            if ($datapoint->getMetric() === $this) {
                $datapoint->setMetric(null);
            }
        }

        return $this;
    }
}
