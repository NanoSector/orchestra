<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Metric;

use Domain\Entity\Datapoint;
use JsonException;
use Web\Helper\Badge;

class TextMetric implements MetricInterface
{
    public function __construct(
        protected string $product,
        protected string $value
    ) {
    }

    public function getName(): string
    {
        return $this->product;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @throws JsonException
     */
    public static function fromDatapoint(Datapoint $datapoint): self
    {
        return new self($datapoint->getMetric()->getProduct(), (string)json_decode($datapoint->getValue(), true, 512, JSON_THROW_ON_ERROR));
    }
}