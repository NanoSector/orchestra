<?php
declare(strict_types=1);

namespace Domain\Metric;

use Domain\Entity\Datapoint;
use Infrastructure\Badge;

interface MetricInterface
{
    public static function fromDatapoint(Datapoint $datapoint): MetricInterface;
    
    public function getName(): string;

    public function getValue(): mixed;

    public function getBadge(): Badge;

    public function __toString(): string;
}