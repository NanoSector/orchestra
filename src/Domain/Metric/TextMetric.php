<?php
declare(strict_types=1);

namespace Domain\Metric;

use Domain\Entity\Datapoint;
use Infrastructure\Badge;

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

    public function getBadge(): Badge
    {
        return Badge::INFO;
    }

    public static function fromDatapoint(Datapoint $datapoint): self
    {
        return new self($datapoint->getMetric()->getProduct(), (string)json_decode($datapoint->getValue(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}