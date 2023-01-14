<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Web\ViewModel;

use Domain\Entity\Datapoint;
use Domain\Entity\Metric;
use Domain\Metric\HealthMetric;
use Domain\Metric\InvalidMetric;
use Domain\Metric\MetricInterface;
use Web\Exception\NoUsableDatapointException;

class MetricViewModel
{
    private MetricInterface $metricObject;
    private Metric $metric;

    private bool $pinned = false;

    public function __construct(Metric $metric, MetricInterface $metricObject)
    {
        $this->metricObject = $metricObject;
        $this->metric = $metric;
    }

    /**
     * @throws NoUsableDatapointException when object can not be constructed because of missing datapoints
     */
    public static function fromLastDatapointInMetric(Metric $metric): self
    {
        $lastDatapoint = $metric->getLastDatapoint();

        if (!$lastDatapoint instanceof Datapoint) {
            throw new NoUsableDatapointException();
        }

        $metricObject = $lastDatapoint->toSpecialist()->makeMetricObject();
        return new self($metric, $metricObject);
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

        return (string)$result;
    }

    public function getId(): ?int
    {
        return $this->metric->getId();
    }

    public function getProduct(): string
    {
        return $this->metricObject->getName();
    }

    public function getName(): string
    {
        return $this->metric->getDiscriminator()->name;
    }

    public function isPinned(): bool
    {
        return $this->pinned;
    }

    public function setPinned(bool $pinned): self
    {
        $this->pinned = $pinned;

        return $this;
    }
}