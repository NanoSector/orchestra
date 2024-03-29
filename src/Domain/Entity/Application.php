<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Orchestra\Domain\Repository\ApplicationDoctrineRepository;
use Orchestra\Infrastructure\Doctrine\Traits\TimestampedEntityTrait;

#[ORM\Entity(repositoryClass: ApplicationDoctrineRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Application
{
    use TimestampedEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'applications')]
    private Collection $groups;

    #[ORM\OneToMany(mappedBy: 'application', targetEntity: Endpoint::class, orphanRemoval: true)]
    private Collection $endpoints;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->endpoints = new ArrayCollection();
    }

    public function addEndpoint(Endpoint $endpoint): self
    {
        if (!$this->endpoints->contains($endpoint)) {
            $this->endpoints->add($endpoint);
            $endpoint->setApplication($this);
        }

        return $this;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }

        return $this;
    }

    /**
     * @return Collection<int, Endpoint>
     */
    public function getEndpoints(): Collection
    {
        return $this->endpoints;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @internal for test use only
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return ArrayCollection<Metric>
     */
    public function getMetrics(): ArrayCollection
    {
        return new ArrayCollection(...$this->endpoints->map(fn(Endpoint $e) => $e->getMetrics()->toArray())->toArray());
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function removeEndpoint(Endpoint $endpoint): self
    {
        if ($this->endpoints->removeElement($endpoint)) {
            // set the owning side to null (unless already changed)
            if ($endpoint->getApplication() === $this) {
                $endpoint->setApplication(null);
            }
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }
}
