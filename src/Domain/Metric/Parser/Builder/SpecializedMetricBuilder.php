<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric\Parser\Builder;

use Orchestra\Domain\Metric\Parser\Node\ParserNodeInterface;
use Orchestra\Domain\Metric\Parser\Node\TextMetricNode;
use Orchestra\Domain\Metric\Parser\Node\TryNode;
use Orchestra\Domain\Metric\Parser\Specialized\CaddyVersionNode;
use Orchestra\Domain\Metric\Parser\Specialized\PostgreSQLVersionNode;

readonly class SpecializedMetricBuilder
{
    public function postgresql(): ParserNodeInterface
    {
        return new PostgreSQLVersionNode();
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
        return new CaddyVersionNode();
    }
}
