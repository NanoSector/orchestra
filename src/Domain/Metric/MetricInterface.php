<?php
declare(strict_types=1);

namespace Domain\Metric;

interface MetricInterface
{
    public function getName(): string;

    public function getValue(): mixed;
}