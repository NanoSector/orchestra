<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Metric;

use JsonException;
use Orchestra\Domain\Entity\Datapoint;

readonly class TextMetric implements MetricInterface
{
    public function __construct(
        protected string $product,
        protected string $value
    ) {
    }

    /**
     * @throws JsonException
     */
    public static function fromDatapoint(Datapoint $datapoint): self
    {
        return new self(
            $datapoint->getMetric()->getProduct(),
            (string)json_decode($datapoint->getValue(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->product;
    }
}