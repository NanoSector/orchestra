<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Metric\Parser\Node;

use JetBrains\PhpStorm\Language;
use Orchestra\Domain\Exception\ParserNodeTypeException;
use Orchestra\Domain\Metric\InvalidMetric;
use Orchestra\Domain\Metric\MetricInterface;
use Orchestra\Domain\Metric\VersionMetric;

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
            throw ParserNodeTypeException::mismatch(got: 'array', wanted: 'string');
        }

        $result = preg_match($this->regex, $value, $matches);

        if ($result !== 1) {
            return new InvalidMetric($this->product);
        }

        return new VersionMetric($this->product, $matches[1]);
    }
}