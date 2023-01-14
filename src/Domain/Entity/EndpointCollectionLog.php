<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Domain\Repository\EndpointCollectionLogRepository;
use Infrastructure\Doctrine\Traits\TimestampedEntityTrait;

#[ORM\Entity(repositoryClass: EndpointCollectionLogRepository::class)]
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
    private ?int $updatedMetricCount = null;

    #[ORM\Column]
    private ?int $createdMetricCount = null;

    #[ORM\Column]
    private ?int $updatedDatapointCount = null;

    #[ORM\Column]
    private ?int $createdDatapointCount = null;

    #[ORM\Column(length: 51000)]
    private ?string $responseBody = null;

    public function getCreatedDatapointCount(): ?int
    {
        return $this->createdDatapointCount;
    }

    public function setCreatedDatapointCount(int $createdDatapointCount): self
    {
        $this->createdDatapointCount = $createdDatapointCount;

        return $this;
    }

    public function getCreatedMetricCount(): ?int
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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUpdatedDatapointCount(): ?int
    {
        return $this->updatedDatapointCount;
    }

    public function setUpdatedDatapointCount(int $updatedDatapointCount): self
    {
        $this->updatedDatapointCount = $updatedDatapointCount;

        return $this;
    }

    public function getUpdatedMetricCount(): ?int
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
