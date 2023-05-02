<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric\Parser\Builder;

use Closure;
use Orchestra\Domain\Metric\Parser\Node\HealthCheckAttributesNode;
use Orchestra\Domain\Metric\Parser\Node\LiteralVersionMetricNode;
use Orchestra\Domain\Metric\Parser\Node\ParserNodeInterface;
use Orchestra\Domain\Metric\Parser\Node\RegexVersionNode;
use Orchestra\Domain\Metric\Parser\Node\TextMetricNode;

readonly class ParserMetricBuilder
{
    public function __construct(
        protected SpecializedMetricBuilder $specializedMetricBuilder
    ) {
    }

    public function healthCheck(string $product, Closure $callable): ParserNodeInterface
    {
        return new HealthCheckAttributesNode($product, $callable);
    }

    public function specialized(): SpecializedMetricBuilder
    {
        return $this->specializedMetricBuilder;
    }

    public function text(string $product): ParserNodeInterface
    {
        return new TextMetricNode($product);
    }

    public function version(string $product): ParserNodeInterface
    {
        return new LiteralVersionMetricNode($product);
    }

    public function versionRegex(string $product, /** @lang RegExp */ string $regex): ParserNodeInterface
    {
        return new RegexVersionNode($product, $regex);
    }
}
