<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Metric;

use JetBrains\PhpStorm\ArrayShape;
use Orchestra\Domain\Entity\Datapoint;

readonly class HealthMetric implements MetricInterface, HealthMetricInterface
{
    public function __construct(
        protected string $product,
        protected bool $healthy,
        #[ArrayShape(['string' => 'mixed'])] protected array $healthAttributes
    ) {
    }

    public static function fromDatapoint(Datapoint $datapoint): self
    {
        return new self($datapoint->getMetric()->getProduct(), (bool)$datapoint->getValue(), []);
    }

    public function getValue(): bool
    {
        return $this->healthy;
    }

    /**
     * @return array<string, mixed>
     */
    public function getHealthAttributes(): array
    {
        return $this->healthAttributes;
    }

    public function getName(): string
    {
        return $this->product;
    }
}