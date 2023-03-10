<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Metric;

use Composer\Semver\Comparator;
use Domain\Entity\Datapoint;
use JsonException;
use Web\Helper\Badge;

readonly class VersionMetric implements SemverMetricInterface
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

    public function isEqualTo(SemverMetricInterface $other): bool
    {
        return Comparator::equalTo($this->getValue(), $other->getValue());
    }

    public function isGreaterThan(SemverMetricInterface $other): bool
    {
        return Comparator::greaterThan($this->getValue(), $other->getValue());
    }

    public function isLessThan(SemverMetricInterface $other): bool
    {
        return Comparator::lessThan($this->getValue(), $other->getValue());
    }

    /**
     * @throws JsonException
     */
    public static function fromDatapoint(Datapoint $datapoint): self
    {
        return new self($datapoint->getMetric()->getProduct(), (string)json_decode($datapoint->getValue(), true, 512, JSON_THROW_ON_ERROR));
    }
}