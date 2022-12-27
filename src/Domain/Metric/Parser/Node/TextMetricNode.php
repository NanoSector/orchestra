<?php
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

    public function parse(string|array $value): MetricInterface
    {
        if (is_array($value)) {
            throw ParserNodeTypeException::mismatch('array', 'string');
        }

        return new TextMetric($this->product, $value);
    }
}