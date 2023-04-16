<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Orchestra\Domain\Repository\GroupRepository;
use Orchestra\Infrastructure\Doctrine\Traits\TimestampedEntityTrait;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
#[ORM\HasLifecycleCallbacks]
class Group
{
    use TimestampedEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groups')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Application::class, mappedBy: 'groups')]
    private Collection $applications;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->applications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->addGroup($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->removeElement($application)) {
            $application->removeGroup($this);
        }

        return $this;
    }
}
