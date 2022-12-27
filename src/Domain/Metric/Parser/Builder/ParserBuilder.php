<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Builder;

use Domain\Metric\Parser\Node\ConditionalNode;
use Domain\Metric\Parser\Node\ParserControlStructureInterface;
use Domain\Metric\Parser\Node\ParserNodeInterface;
use Domain\Metric\Parser\Node\RequiredNode;
use Domain\Metric\Parser\Node\SwitchNode;
use Domain\Metric\Parser\Node\TryNode;

class ParserBuilder
{
    private ParserMetricBuilder $metricBuilder;

    public function __construct(ParserMetricBuilder $metricBuilder)
    {
        $this->metricBuilder = $metricBuilder;
    }

    public function if(callable $condition, array $ifTrue, array $ifFalse): ParserControlStructureInterface
    {
        return new ConditionalNode($condition, $ifTrue, $ifFalse);
    }

    public function switch(callable $reducer, array $cases): ParserControlStructureInterface
    {
        return new SwitchNode($reducer, $cases);
    }

    public function anyOf(ParserNodeInterface...$nodes): ParserNodeInterface
    {
        return new TryNode(...$nodes);
    }

    public function required(array $children): ParserControlStructureInterface
    {
        return new RequiredNode($children);
    }

    public function metric(): ParserMetricBuilder
    {
        return $this->metricBuilder;
    }
}