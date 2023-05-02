<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint;

use JsonException;
use Orchestra\Domain\Endpoint\Driver\DriverResponseInterface;
use Orchestra\Domain\Endpoint\Driver\DriverResponseWithBodyInterface;
use Orchestra\Domain\Entity\Datapoint;
use Orchestra\Domain\Entity\Endpoint;
use Orchestra\Domain\Entity\EndpointCollectionLog;
use Orchestra\Domain\Entity\Metric;
use Orchestra\Domain\Metric\MetricEnum;

readonly class EndpointDriverResponseMapper
{
    /**
     * Calculates the diff between the current Endpoint state and the desired state in the response.
     */
    public function createCollectionLog(Endpoint $endpoint, DriverResponseInterface $response): EndpointCollectionLog
    {
        $log = new EndpointCollectionLog();
        $log->setSuccessful(true);

        if ($response instanceof DriverResponseWithBodyInterface) {
            $log->setResponseBody($response->getResponseBody());
        }

        $metrics = $response->getMetrics();
        $log->setMetricsMissingInResponseCount(
            count($metrics) - $endpoint->getMetrics()->count()
        );

        foreach ($metrics as $metric) {
            $metricEntity = $endpoint->getMetrics()->findFirst(
                fn($key, Metric $m) => $m->getProduct() === $metric->getName()
                    && $m->getDiscriminator()->value === get_class($metric)
            );

            if (!$metricEntity instanceof Metric) {
                // Mock one so we can continue.
                $metricEntity = new Metric();

                $log->setCreatedMetricCount($log->getCreatedMetricCount() + 1);
            } else {
                $log->setUpdatedMetricCount($log->getUpdatedMetricCount() + 1);
            }

            $lastDatapoint = $metricEntity->getLastDatapoint();
            $newValue = json_encode($metric->getValue(), JSON_THROW_ON_ERROR);

            if ($lastDatapoint instanceof Datapoint && $newValue === $lastDatapoint->getValue()) {
                $log->setUpdatedDatapointCount($log->getUpdatedDatapointCount() + 1);
            } else {
                $log->setCreatedDatapointCount($log->getCreatedDatapointCount() + 1);
            }
        }

        return $log;
    }

    /**
     * Maps the metrics in the given response onto the given endpoint object,
     * creating or updating Metrics and Datapoints as necessary.
     *
     * @throws JsonException
     */
    public function map(Endpoint $endpoint, DriverResponseInterface $response): Endpoint
    {
        foreach ($response->getMetrics() as $metric) {
            $metricEntity = $endpoint->getMetrics()->findFirst(
                fn($key, Metric $m) => $m->getProduct() === $metric->getName()
                    && $m->getDiscriminator()->value === get_class($metric)
            );

            if (!$metricEntity instanceof Metric) {
                $metricEntity = new Metric();
                $metricEntity->setEndpoint($endpoint);
                $metricEntity->setProduct($metric->getName());
                $metricEntity->setDiscriminator(MetricEnum::from(get_class($metric)));

                $endpoint->addMetric($metricEntity);
            }

            $lastDatapoint = $metricEntity->getLastDatapoint();
            $newValue = json_encode($metric->getValue(), JSON_THROW_ON_ERROR);

            if ($lastDatapoint instanceof Datapoint && $newValue === $lastDatapoint->getValue()) {
                $lastDatapoint->touchUpdatedAt();

                continue;
            }

            $datapoint = new Datapoint();
            $datapoint->setValue($newValue);
            $datapoint->touchUpdatedAt();
            $metricEntity->addDatapoint($datapoint);
        }

        return $endpoint;
    }
}
