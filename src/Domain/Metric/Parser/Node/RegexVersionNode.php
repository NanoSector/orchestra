<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

use Domain\Exception\ParserNodeTypeException;
use Domain\Metric\InvalidMetric;
use Domain\Metric\MetricInterface;
use Domain\Metric\VersionMetric;

/**
 * Matches the given regex against the version.
 * Will use the first capturing group as value.
 */
class RegexVersionNode implements ParserNodeInterface
{
    private string $product;
    private string $regex;

    public function __construct(string $product, /** @lang RegExp */ string $regex)
    {
        $this->product = $product;
        $this->regex = $regex;
    }

    /**
     * @throws ParserNodeTypeException
     */
    public function parse(string|array $value): MetricInterface
    {
        if (is_array($value)) {
            throw ParserNodeTypeException::mismatch('array', 'string');
        }

        $result = preg_match($this->regex, $value, $matches);

        if ($result !== 1) {
            return new InvalidMetric($this->product);
        }

        return new VersionMetric($this->product, $matches[1] );
    }
}