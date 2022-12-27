<?php
declare(strict_types=1);

namespace Domain\Metric;

class InvalidMetric implements MetricInterface
{
    protected string $product;

    public function __construct(string $product)
    {
        $this->product = $product;
    }

    public function getName(): string
    {
        return $this->product;
    }

    public function getValue(): mixed
    {
        return null;
    }
}