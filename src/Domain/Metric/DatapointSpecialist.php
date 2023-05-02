<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric;

use InvalidArgumentException;
use Orchestra\Domain\Entity\Datapoint;
use Orchestra\Domain\Entity\Metric;

readonly class DatapointSpecialist
{
    public function __construct(
        private Datapoint $datapoint
    ) {
    }

    public function makeMetricObject(): MetricInterface
    {
        $metric = $this->datapoint->getMetric();
        if (!$metric instanceof Metric) {
            throw new InvalidArgumentException('Cannot specialize a detached Datapoint object');
        }

        switch ($metric->getDiscriminator()) {
            case MetricEnum::Health:
                return new HealthMetric($metric->getProduct(), (bool)$this->datapoint->getValue(), []);

            case MetricEnum::Invalid:
            case null:
                return new InvalidMetric($metric->getProduct());

            case MetricEnum::Text:
                return new TextMetric($metric->getProduct(), $this->datapoint->getValue());

            case MetricEnum::Version:
                return new VersionMetric($metric->getProduct(), $this->datapoint->getValue());
        }

        throw new InvalidArgumentException(
            sprintf('Do not know how to specialize %s', $metric->getDiscriminator()?->value ?? 'Unknown')
        );
    }
}
