<?php
declare(strict_types=1);

namespace Domain\Metric;

use Domain\Entity\Datapoint;
use Infrastructure\Badge;

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

    public function getBadge(): Badge
    {
        return Badge::DANGER;
    }

    public static function fromDatapoint(Datapoint $datapoint): self
    {
        return new self($datapoint->getMetric()->getProduct());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'N/A';
    }
}