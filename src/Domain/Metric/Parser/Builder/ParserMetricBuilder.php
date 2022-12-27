<?php

namespace Domain\Metric\Parser\Builder;

use Domain\Metric\Parser\Node\HealthCheckAttributesNode;
use Domain\Metric\Parser\Node\LiteralVersionMetricNode;
use Domain\Metric\Parser\Node\ParserNodeInterface;
use Domain\Metric\Parser\Node\RegexVersionNode;
use Domain\Metric\Parser\Node\TextMetricNode;

class ParserMetricBuilder
{
    private SpecializedMetricBuilder $specializedMetricBuilder;

    public function __construct(SpecializedMetricBuilder $specializedMetricBuilder)
    {
        $this->specializedMetricBuilder = $specializedMetricBuilder;
    }

    public function healthCheck(string $product, callable $callable): ParserNodeInterface
    {
        return new HealthCheckAttributesNode($product, $callable);
    }

    public function version(string $product): ParserNodeInterface
    {
        return new LiteralVersionMetricNode($product);
    }

    public function versionRegex(string $product, /** @lang RegExp */string $regex): ParserNodeInterface
    {
        return new RegexVersionNode($product, $regex);
    }

    public function text(string $product): ParserNodeInterface
    {
        return new TextMetricNode($product);
    }

    public function specialized(): SpecializedMetricBuilder
    {
        return $this->specializedMetricBuilder;
    }
}