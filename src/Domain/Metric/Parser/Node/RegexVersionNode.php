<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Metric\Parser\Node;

use Domain\Exception\ParserNodeTypeException;
use Domain\Metric\InvalidMetric;
use Domain\Metric\MetricInterface;
use Domain\Metric\VersionMetric;
use JetBrains\PhpStorm\Language;

/**
 * Matches the given regex against the version.
 * Will use the first capturing group as value.
 */
readonly class RegexVersionNode implements ParserNodeInterface
{
    protected string $regex;

    public function __construct(
        protected string $product,
        #[Language('RegExp')] string $regex
    ) {
        // This is a property so that we can apply the Language attribute.
        $this->regex = $regex;
    }

    /**
     * @throws ParserNodeTypeException
     */
    public function parse(string|array $value): MetricInterface
    {
        if (is_array($value)) {
            throw ParserNodeTypeException::mismatch('array', 'string');
        }

        $result = preg_match($this->regex, $value, $matches);

        if ($result !== 1) {
            return new InvalidMetric($this->product);
        }

        return new VersionMetric($this->product, $matches[1]);
    }
}