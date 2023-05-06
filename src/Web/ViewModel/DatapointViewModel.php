<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\ViewModel;

use Carbon\Carbon;
use Generator;
use Orchestra\Domain\Entity\Datapoint;
use Orchestra\Domain\Entity\Metric;
use Orchestra\Domain\Metric\MetricInterface;

readonly class DatapointViewModel
{
    public function __construct(
        private Datapoint $datapoint,
        private MetricInterface $metricObject
    ) {
    }

    /**
     * @param Metric $metric
     * @return Generator<DatapointViewModel>
     */
    public static function fromAllDatapointsInMetric(Metric $metric): Generator
    {
        foreach ($metric->getDatapoints() as $datapoint) {
            $metricObject = $datapoint->toSpecialist()->makeMetricObject();
            yield new self($datapoint, $metricObject);
        }
    }

    public function getId(): ?int
    {
        return $this->datapoint->getId();
    }

    public function getValue()
    {
        return $this->metricObject->getValue();
    }

    public function hasBeenTouched(): bool
    {
        if (!$this->datapoint->getUpdatedAt() instanceof Carbon) {
            return false;
        }

        return $this->datapoint->getUpdatedAt()->isAfter(
            $this->datapoint->getCreatedAt()
        );
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->datapoint->getUpdatedAt();
    }

    public function getCreatedAt(): Carbon
    {
        return $this->datapoint->getCreatedAt();
    }
}
