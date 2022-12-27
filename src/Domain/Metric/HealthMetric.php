<?php
declare(strict_types=1);

namespace Domain\Metric;

use Domain\Entity\Datapoint;
use Infrastructure\Badge;

class HealthMetric implements MetricInterface, HealthMetricInterface
{
    protected string $product;

    protected bool $healthy;

    protected array $attributes;

    public function __construct(string $product, bool $healthy, array $attributes)
    {
        $this->product = $product;
        $this->healthy = $healthy;
        $this->attributes = $attributes;
    }


    public function getName(): string
    {
        return $this->product;
    }

    public function getValue(): bool
    {
        return $this->healthy;
    }

    public function getBadge(): Badge
    {
        return $this->getValue() ? Badge::OK : Badge::DANGER;
    }

    /**
     * @return array<string, mixed>
     */
    public function getHealthAttributes(): array
    {
        return $this->attributes;
    }

    public static function fromDatapoint(Datapoint $datapoint): self
    {
        return new self($datapoint->getMetric()->getProduct(), (bool)$datapoint->getValue(), []);
    }

    public function __toString(): string
    {
        return $this->getValue() ? 'Healthy' : 'Unhealthy';
    }
}