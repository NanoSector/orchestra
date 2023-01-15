<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Metric;

use Domain\Entity\Datapoint;

readonly class InvalidMetric implements MetricInterface
{

    public function __construct(
        protected string $product
    ) {
    }

    public static function fromDatapoint(Datapoint $datapoint): self
    {
        return new self($datapoint->getMetric()->getProduct());
    }

    public function getName(): string
    {
        return $this->product;
    }

    public function getValue(): null
    {
        return null;
    }
}