<?php

namespace Domain\Metric\Parser\Builder;

use Domain\Metric\Parser\Node\ParserNodeInterface;
use Domain\Metric\Parser\Node\RegexVersionNode;
use Domain\Metric\Parser\Node\TextMetricNode;
use Domain\Metric\Parser\Node\TryNode;

class SpecializedMetricBuilder
{
    public function postgresql(): ParserNodeInterface
    {
        return new RegexVersionNode('PostgreSQL', '/^PostgreSQL ([0-9\.]+)/');
    }

    public function caddy(): ParserNodeInterface
    {
        return new RegexVersionNode('Caddy', '/^Caddy\/v(.+)$/');
    }

    public function webserverHeader(): ParserNodeInterface
    {
        return new TryNode(
            $this->caddy(),
            new TextMetricNode('Webserver')
        );
    }
}