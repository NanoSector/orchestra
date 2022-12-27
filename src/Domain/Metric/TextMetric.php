<?php
declare(strict_types=1);

namespace Domain\Metric;

class TextMetric implements MetricInterface
{
    protected string $product;
    protected string $value;

    public function __construct(string $product, string $value)
    {
        $this->product = $product;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->product;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}