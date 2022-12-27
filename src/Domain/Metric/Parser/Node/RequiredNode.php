<?php
declare(strict_types=1);

namespace Domain\Metric\Parser\Node;

use Domain\Exception\RequiredParserNodeNotFoundException;

class RequiredNode extends AbstractParserControlStructure
{

    private array $children;

    public function __construct(array $children)
    {
        $this->children = $children;
    }

    public function parse(array|string $value): array
    {
        return $this->children;
    }

    /**
     * @throws RequiredParserNodeNotFoundException
     */
    public function handleNotFound(): void
    {
        throw new RequiredParserNodeNotFoundException();
    }
}