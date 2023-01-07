<?php

declare(strict_types=1);

namespace Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Domain\Collection\RoleCollection;
use Domain\Enumeration\Role;
use Domain\Repository\UserRepository;
use Infrastructure\Doctrine\Traits\SoftDeleteEntityTrait;
use Infrastructure\Doctrine\Traits\TimestampedEntityTrait;
use InvalidArgumentException;
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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MetricPin::class, cascade: ['persist', 'remove'])]
    private Collection $pinnedMetrics;

    public function __construct()
    {
        $this->roles = new RoleCollection([Role::ROLE_USER]);
        $this->groups = new ArrayCollection();
        $this->pinnedMetrics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles->asStringCollection()->toArray();
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

    public function addFormRole(Role $role): void
    {
        $this->roles->add($role);
        $this->roles = $this->roles->unique();
    }

    public function removeFormRole(Role $role): void
    {
        if ($role === Role::ROLE_USER) {
            // Cannot remove the default user role.
            return;
        }

        $this->roles = $this->roles->filter(fn(Role $r) => !$r->equals($role));
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addUser($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, MetricPin>
     */
    public function getPinnedMetrics(): Collection
    {
        return $this->pinnedMetrics;
    }

    public function pinMetric(Metric $metric): self
    {
        if (!$this->pinnedMetrics->findFirst(fn(int $key, MetricPin $p) => $p->getMetric() === $metric)) {
            $pin = new MetricPin($metric, $this);
            $this->pinnedMetrics->add($pin);
        }

        return $this;
    }

    public function unpinMetric(Metric $metric): self
    {
//        $this->pinnedMetrics = $this->pinnedMetrics->filter(fn(MetricPin $p) => $p->getMetric() !== $metric);

        return $this;
    }
}
