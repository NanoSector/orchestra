<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

use Domain\Exception\ParserNodeTypeException;
use Domain\Metric\MetricInterface;
use Domain\Metric\TextMetric;

class TextMetricNode implements ParserNodeInterface
{
    private string $product;

    public function __construct(string $product)
    {
        $this->product = $product;
    }

    /**
     * @throws ParserNodeTypeException
     */
    public function parse(string|array $value): MetricInterface
    {
        if (is_array($value)) {
            throw ParserNodeTypeException::mismatch('array', 'string');
        }

        return new TextMetric($this->product, $value);
    }
}