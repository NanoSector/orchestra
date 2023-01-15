<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Metric\Parser\Node;

use Domain\Exception\CompositeParserExhaustedException;
use Domain\Metric\InvalidMetric;
use Domain\Metric\MetricInterface;

/**
 * Takes multiple parser nodes and exhausts the list until
 * one returns a valid metric
 */
readonly class TryNode implements ParserNodeInterface
{
    /** @var ParserNodeInterface[] */
    private array $nodes;

    public function __construct(ParserNodeInterface...$nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @throws CompositeParserExhaustedException
     */
    public function parse(string|array $value): MetricInterface
    {
        foreach ($this->nodes as $node) {
            $result = $node->parse($value);

            if (!$result instanceof InvalidMetric) {
                return $result;
            }
        }

        throw new CompositeParserExhaustedException();
    }
}