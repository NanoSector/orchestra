<?php
declare(strict_types=1);

namespace Domain\Metric;

use Domain\Entity\Datapoint;
use Web\Helper\Badge;

interface MetricInterface
{
    public function getName(): string;

    public function getValue(): mixed;
}