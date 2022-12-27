<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

use Domain\Metric\MetricInterface;

interface ParserNodeInterface
{
    public function parse(string|array $value): MetricInterface;
}