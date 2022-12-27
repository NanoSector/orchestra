<?php
declare(strict_types=1);

namespace Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Domain\Endpoint\Driver\DriverEndpointInterface;
use Domain\Endpoint\EndpointDriver;
use Domain\Repository\EndpointRepository;
use Infrastructure\Doctrine\Traits\TimestampedEntityTrait;

#[ORM\Entity(repositoryClass: EndpointRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Endpoint implements DriverEndpointInterface
{
    use TimestampedEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'endpoints')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Application $application = null;

    #[ORM\Column(length: 255)]
    private string $url = '';

    #[ORM\Column(type: 'EndpointDriverType', length: 255)]
    private ?EndpointDriver $driver = null;

    #[ORM\Column]
    private array $driverOptions = [];

    #[ORM\Column]
    private ?int $interval = null;

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

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDriver(): ?EndpointDriver
    {
        return $this->driver;
    }

    public function setDriver(EndpointDriver $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getDriverOptions(): array
    {
        return $this->driverOptions;
    }

    public function setDriverOptions(array $driverOptions): self
    {
        $this->driverOptions = $driverOptions;

        return $this;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

        return $this;
    }
}
