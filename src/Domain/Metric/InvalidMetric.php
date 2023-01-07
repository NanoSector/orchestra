<?php
declare(strict_types=1);

namespace Domain\Metric;

use Domain\Entity\Datapoint;
use Web\Helper\Badge;

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

    public static function fromDatapoint(Datapoint $datapoint): self
    {
        return new self($datapoint->getMetric()->getProduct());
    }
}