<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\ViewModel;

use JsonException;
use Orchestra\Domain\Entity\Datapoint;
use Orchestra\Domain\Entity\Metric;
use Orchestra\Domain\Metric\HealthMetric;
use Orchestra\Domain\Metric\InvalidMetric;
use Orchestra\Domain\Metric\MetricInterface;
use Orchestra\Web\Exception\NoUsableDatapointException;

class MetricViewModel
{
    private bool $pinned = false;

    public function __construct(
        private readonly Metric $metric,
        private readonly MetricInterface $metricObject
    ) {
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

        try {
            $result = json_decode((string)$this->metricObject->getValue(), true, 512, JSON_THROW_ON_ERROR);

            if (is_array($result)) {
                return 'Invalid value';
            }
        } catch (JsonException) {
            return 'Invalid value';
        }

        return (string)$result;
    }

    public function getMetricId(): ?int
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
