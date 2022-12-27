<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

class ConditionalNode extends AbstractParserControlStructure
{
    /**
     * @var callable(string): bool
     */
    private $condition;
    private array $ifTrue;
    private array $ifFalse;

    /**
     * @param callable(string|array): bool $condition
     * @param array $ifTrue
     * @param array $ifFalse
     */
    public function __construct(callable $condition, array $ifTrue, array $ifFalse = [])
    {
        $this->condition = $condition;
        $this->ifTrue = $ifTrue;
        $this->ifFalse = $ifFalse;
    }

    public function parse(string|array $value): array
    {
        $result = call_user_func($this->condition, $value);

        return $result ? $this->ifTrue : $this->ifFalse;
    }
}