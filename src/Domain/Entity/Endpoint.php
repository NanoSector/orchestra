<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Domain\Endpoint\Driver\DriverEndpointInterface;
use Domain\Endpoint\Driver\DriverEnum;
use Domain\Repository\EndpointRepository;
use Infrastructure\Doctrine\Traits\TimestampedEntityTrait;
use Symfony\Component\Validator\Constraints;

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
    private ?DriverEnum $driver = null;

    #[ORM\Column]
    private array $driverOptions = [];

    #[ORM\Column]
    private ?int $interval = null;

    #[ORM\Column]
    #[Constraints\PositiveOrZero]
    private int $collectionLogRetention = 604800; // in seconds; default is a week

    #[ORM\Column(type: 'CarbonDateTimeType', nullable: true)]
    private ?Carbon $lastSuccessfulResponse = null;

    #[ORM\OneToMany(mappedBy: 'endpoint', targetEntity: Metric::class, cascade: ['persist'], fetch: 'EAGER', orphanRemoval: true)]
    private Collection $metrics;

    #[ORM\OneToMany(mappedBy: 'endpoint', targetEntity: EndpointCollectionLog::class, cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy([ 'id' => 'DESC' ])]
    private Collection $collectionLogs;

    public function __construct()
    {
        $this->metrics = new ArrayCollection();
        $this->collectionLogs = new ArrayCollection();
    }

    public function addCollectionLog(EndpointCollectionLog $collectionLog): self
    {
        if (!$this->collectionLogs->contains($collectionLog)) {
            $this->collectionLogs->add($collectionLog);
            $collectionLog->setEndpoint($this);
        }

        return $this;
    }

    public function addMetric(Metric $metric): self
    {
        if (!$this->metrics->contains($metric)) {
            $this->metrics->add($metric);
            $metric->setEndpoint($this);
        }

        return $this;
    }

    public function belongsToApplication(Application $application): bool
    {
        if (!$this->application instanceof Application) {
            return false;
        }

        return $application->getId() === $this->application->getId();
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

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @return Collection<int, EndpointCollectionLog>
     */
    public function getCollectionLogs(): Collection
    {
        return $this->collectionLogs;
    }

    public function getDriver(): ?DriverEnum
    {
        return $this->driver;
    }

    public function setDriver(DriverEnum $driver): self
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

    public function getLastSuccessfulResponse(): ?Carbon
    {
        return $this->lastSuccessfulResponse;
    }

    public function setLastSuccessfulResponse(?Carbon $lastSuccessfulResponse): self
    {
        $this->lastSuccessfulResponse = $lastSuccessfulResponse;

        return $this;
    }

    /**
     * @return ArrayCollection<string, ArrayCollection<Metric>>
     */
    public function getMetricsPerProduct(): ArrayCollection
    {
        $collection = new ArrayCollection();

        foreach ($this->getMetrics() as $metric) {
            if (!$collection->containsKey($metric->getProduct())) {
                $collection->set($metric->getProduct(), new ArrayCollection());
            }

            $collection->get($metric->getProduct())->add($metric);
        }

        return $collection;
    }

    /**
     * @return Collection<int, Metric>
     */
    public function getMetrics(): Collection
    {
        return $this->metrics;
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

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function removeCollectionLog(EndpointCollectionLog $collectionLog): self
    {
        if ($this->collectionLogs->removeElement($collectionLog)) {
            // set the owning side to null (unless already changed)
            if ($collectionLog->getEndpoint() === $this) {
                $collectionLog->setEndpoint(null);
            }
        }

        return $this;
    }

    public function removeMetric(Metric $metric): self
    {
        if ($this->metrics->removeElement($metric)) {
            // set the owning side to null (unless already changed)
            if ($metric->getEndpoint() === $this) {
                $metric->setEndpoint(null);
            }
        }

        return $this;
    }

    public function touchLastSuccessfulResponse(): self
    {
        $this->lastSuccessfulResponse = Carbon::now();

        return $this;
    }


}
