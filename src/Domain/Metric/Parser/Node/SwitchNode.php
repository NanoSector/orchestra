<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

use InvalidArgumentException;

class SwitchNode extends AbstractParserControlStructure
{
    public const DEFAULT = '_default';

    /**
     * @var callable
     */
    private $reducer;
    private array $cases;

    /**
     * @param callable(array|string): string $reducer
     * @param array[] $cases
     */
    public function __construct(callable $reducer, array $cases)
    {
        $this->reducer = $reducer;
        $this->cases = $cases;
    }

    public function parse(string|array $value): array
    {
        $reduced = call_user_func($this->reducer, $value);

        if (!is_string($reduced)) {
            throw new InvalidArgumentException('SwitchNode expects a callback that reduces the value to a string');
        }

        foreach ($this->cases as $case => $result) {
            if ($reduced === $case) {
                return $result;
            }
        }

        if (array_key_exists(self::DEFAULT, $this->cases)) {
            return $this->cases[self::DEFAULT];
        }

        return [];
    }
}