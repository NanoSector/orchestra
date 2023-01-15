<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

namespace Domain\Metric\Parser\Builder;

use Domain\Metric\Parser\Node\ParserNodeInterface;
use Domain\Metric\Parser\Node\RegexVersionNode;
use Domain\Metric\Parser\Node\TextMetricNode;
use Domain\Metric\Parser\Node\TryNode;

readonly class SpecializedMetricBuilder
{
    public function postgresql(): ParserNodeInterface
    {
        return new RegexVersionNode('PostgreSQL', '/^PostgreSQL ([0-9\.]+)/');
    }

    public function webserverHeader(): ParserNodeInterface
    {
        return new TryNode(
            $this->caddy(),
            new TextMetricNode('Webserver')
        );
    }

    public function caddy(): ParserNodeInterface
    {
        return new RegexVersionNode('Caddy', '/^Caddy\/v(.+)$/');
    }
}