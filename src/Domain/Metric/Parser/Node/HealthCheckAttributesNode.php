<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

use Domain\Metric\HealthMetric;
use Domain\Metric\MetricInterface;

class HealthCheckAttributesNode implements ParserNodeInterface
{
    /**
     * @var callable
     */
    private $condition;
    private string $product;

    /**
     * @param string $product
     * @param callable(string|array): bool $condition
     */
    public function __construct(string $product, callable $condition)
    {
        $this->condition = $condition;
        $this->product = $product;
    }

    public function parse(array|string $value): MetricInterface
    {
        $result = call_user_func($this->condition, $value);

        $attributes = !is_array($value) ? [$value] : $value;

        return new HealthMetric($this->product, (bool)$result, $attributes);
    }
}