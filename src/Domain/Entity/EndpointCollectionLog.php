<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orchestra\Domain\Repository\EndpointCollectionLogDoctrineRepository;
use Orchestra\Infrastructure\Doctrine\Traits\TimestampedEntityTrait;

#[ORM\Entity(repositoryClass: EndpointCollectionLogDoctrineRepository::class)]
#[ORM\HasLifecycleCallbacks]
class EndpointCollectionLog
{
    use TimestampedEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'collectionLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Endpoint $endpoint = null;

    #[ORM\Column]
    private ?bool $successful = null;

    #[ORM\Column]
    private int $updatedMetricCount = 0;

    #[ORM\Column]
    private int $createdMetricCount = 0;

    #[ORM\Column]
    private int $updatedDatapointCount = 0;

    #[ORM\Column]
    private int $createdDatapointCount = 0;

    #[ORM\Column]
    private int $metricsMissingInResponseCount = 0;

    #[ORM\Column(length: 51000, nullable: true)]
    private ?string $responseBody = null;

    public function belongsToEndpoint(Endpoint $endpoint): bool
    {
        if (!$this->endpoint instanceof Endpoint) {
            return false;
        }

        return $endpoint->getId() === $this->endpoint->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedDatapointCount(): int
    {
        return $this->createdDatapointCount;
    }

    public function setCreatedDatapointCount(int $createdDatapointCount): self
    {
        $this->createdDatapointCount = $createdDatapointCount;

        return $this;
    }

    public function getCreatedMetricCount(): int
    {
        return $this->createdMetricCount;
    }

    public function setCreatedMetricCount(int $createdMetricCount): self
    {
        $this->createdMetricCount = $createdMetricCount;

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

    public function getMetricsMissingInResponseCount(): int
    {
        return $this->metricsMissingInResponseCount;
    }

    public function setMetricsMissingInResponseCount(int $metricsMissingInResponseCount): self
    {
        $this->metricsMissingInResponseCount = $metricsMissingInResponseCount;

        return $this;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    public function setResponseBody(string $responseBody): self
    {
        $this->responseBody = $responseBody;

        return $this;
    }

    public function getUpdatedDatapointCount(): int
    {
        return $this->updatedDatapointCount;
    }

    public function setUpdatedDatapointCount(int $updatedDatapointCount): self
    {
        $this->updatedDatapointCount = $updatedDatapointCount;

        return $this;
    }

    public function getUpdatedMetricCount(): int
    {
        return $this->updatedMetricCount;
    }

    public function setUpdatedMetricCount(int $updatedMetricCount): self
    {
        $this->updatedMetricCount = $updatedMetricCount;

        return $this;
    }

    public function isSuccessful(): ?bool
    {
        return $this->successful;
    }

    public function setSuccessful(bool $successful): self
    {
        $this->successful = $successful;

        return $this;
    }
}
