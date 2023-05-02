<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric\Parser\Builder;

use Closure;
use Orchestra\Domain\Metric\Parser\Node\ApplyToEachNode;
use Orchestra\Domain\Metric\Parser\Node\ConditionalNode;
use Orchestra\Domain\Metric\Parser\Node\ParserControlStructureInterface;
use Orchestra\Domain\Metric\Parser\Node\ParserNodeInterface;
use Orchestra\Domain\Metric\Parser\Node\RequiredNode;
use Orchestra\Domain\Metric\Parser\Node\SwitchNode;
use Orchestra\Domain\Metric\Parser\Node\TryNode;

readonly class ParserBuilder
{
    public function __construct(
        protected ParserMetricBuilder $metricBuilder
    ) {
    }

    public function anyOf(ParserNodeInterface ...$nodes): ParserNodeInterface
    {
        return new TryNode(...$nodes);
    }

    public function each(Closure $consumer): ParserControlStructureInterface
    {
        return new ApplyToEachNode($consumer);
    }

    public function if(Closure $condition, array $ifTrue, array $ifFalse): ParserControlStructureInterface
    {
        return new ConditionalNode($condition, $ifTrue, $ifFalse);
    }

    public function metric(): ParserMetricBuilder
    {
        return $this->metricBuilder;
    }

    public function required(array $children): ParserControlStructureInterface
    {
        return new RequiredNode($children);
    }

    public function switch(callable $reducer, array $cases): ParserControlStructureInterface
    {
        return new SwitchNode($reducer, $cases);
    }
}
