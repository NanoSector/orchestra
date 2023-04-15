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
use InvalidArgumentException;
use Orchestra\Domain\Collection\RoleCollection;
use Orchestra\Domain\Entity\Decorator\PinnedMetricUserDecorator;
use Orchestra\Domain\Enumeration\Role;
use Orchestra\Domain\Repository\UserRepository;
use Orchestra\Infrastructure\Doctrine\Traits\SoftDeleteEntityTrait;
use Orchestra\Infrastructure\Doctrine\Traits\TimestampedEntityTrait;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampedEntityTrait;
    use SoftDeleteEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private string $username;

    #[Constraints\Email]
    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column(name: 'roles', type: 'RoleCollectionType')]
    private RoleCollection $roles;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Constraints\NotCompromisedPassword]
    private string $password;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'users', fetch: 'EAGER')]
    private Collection $groups;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MetricPin::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $pinnedMetrics;

    public function __construct()
    {
        $this->roles = new RoleCollection([Role::ROLE_USER]);
        $this->groups = new ArrayCollection();
        $this->pinnedMetrics = new ArrayCollection();
    }

    public function addFormRole(Role $role): void
    {
        $this->roles->add($role);
        $this->roles = $this->roles->unique();
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addUser($this);
        }

        return $this;
    }

    public function decoratePinnedMetrics(): PinnedMetricUserDecorator
    {
        return new PinnedMetricUserDecorator($this);
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $validated = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($validated === false) {
            throw new InvalidArgumentException('E-mail validation failed');
        }

        $this->email = $email;
    }

    /**
     * Used by the Symfony form builder component.
     *
     * @return Role[]
     */
    public function getFormRoles(): array
    {
        return $this->roles->toArray();
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, MetricPin>
     */
    public function getPinnedMetrics(): Collection
    {
        return $this->pinnedMetrics;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles->asStringCollection()->toArray();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function removeFormRole(Role $role): void
    {
        if ($role === Role::ROLE_USER) {
            // Cannot remove the default user role.
            return;
        }

        $this->roles = $this->roles->filter(fn(Role $r) => !$r->equals($role));
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeUser($this);
        }

        return $this;
    }
}
