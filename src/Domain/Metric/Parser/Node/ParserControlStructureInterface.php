<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

interface ParserControlStructureInterface
{
    public function parse(string|array $value): array;

    public function handleNotFound(): void;
}