<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

use Domain\Exception\ParserNodeTypeException;
use Domain\Metric\VersionMetric;

class LiteralVersionMetricNode implements ParserNodeInterface
{
    private string $product;

    public function __construct(string $product)
    {
        $this->product = $product;
    }

    /**
     * @throws ParserNodeTypeException
     */
    public function parse(string|array $value): VersionMetric
    {
        if (is_array($value)) {
            throw ParserNodeTypeException::mismatch('array', 'string');
        }

        // TODO parse version
        return new VersionMetric($this->product, $value);
    }
}