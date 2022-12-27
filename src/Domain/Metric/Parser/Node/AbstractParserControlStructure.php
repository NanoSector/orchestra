<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

abstract class AbstractParserControlStructure implements ParserControlStructureInterface
{
    public function handleNotFound(): void
    {
        // No-op, most structures do not need to handle this
    }

}