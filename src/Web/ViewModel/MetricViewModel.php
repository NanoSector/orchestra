<?php
declare(strict_types=1);

namespace Web\ViewModel;

use Domain\Entity\Endpoint;
use Domain\Entity\Metric;
use Domain\Metric\HealthMetric;
use Domain\Metric\InvalidMetric;
use Domain\Metric\MetricEnum;
use Domain\Metric\MetricInterface;

class MetricViewModel
{
    private MetricInterface $metricObject;
    private Metric $metric;

    public function __construct(Metric $metric, MetricInterface $metricObject)
    {
        $this->metricObject = $metricObject;
        $this->metric = $metric;
    }

    public function getClassList(): string
    {
        $classes = ['bg-gradient', 'text-white'];
        if ($this->metricObject instanceof HealthMetric) {
            if ($this->metricObject->getValue()) {
                $classes[] = 'bg-success';
            } else {
                $classes[] = 'bg-danger';
            }
        } elseif ($this->metricObject instanceof InvalidMetric) {
            $classes[] = 'bg-danger';
        } else {
            $classes[] = 'bg-secondary';
        }

        return implode(' ', $classes);
    }

    public function getId(): ?int
    {
        return $this->metric->getId();
    }

    public function getName(): string
    {
        return $this->metric->getDiscriminator()->name;
    }

    public function getProduct(): string
    {
        return $this->metricObject->getName();
    }

    public function getValue(): string
    {
        if ($this->metricObject instanceof HealthMetric) {
            return $this->metricObject->getValue() ? 'Healthy' : 'Unhealthy';
        }

        if ($this->metricObject instanceof InvalidMetric) {
            return 'N/A';
        }

        $result = json_decode($this->metricObject->getValue(), true, 512, JSON_THROW_ON_ERROR);

        if (is_array($result)) {
            return 'Invalid value';
        }

        return (string) $result;
    }
}