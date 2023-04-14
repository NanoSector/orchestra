<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Metric\Parser\Node;

use Domain\Exception\ParserNodeTypeException;
use Domain\Metric\VersionMetric;

readonly class LiteralVersionMetricNode implements ParserNodeInterface
{
    public function __construct(
        protected string $product
    ) {
    }

    /**
     * @throws ParserNodeTypeException
     */
    public function parse(string|array $value): VersionMetric
    {
        if (is_array($value)) {
            throw ParserNodeTypeException::mismatch(got: 'array', wanted: 'string');
        }

        // TODO parse version
        return new VersionMetric($this->product, $value);
    }
}