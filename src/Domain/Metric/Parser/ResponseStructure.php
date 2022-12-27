<?php
declare(strict_types=1);

namespace Domain\Metric\Parser;

use Domain\Metric\MetricInterface;
use Domain\Metric\Parser\Node\ParserControlStructureInterface;
use Domain\Metric\Parser\Node\ParserNodeInterface;

class ResponseStructure
{
    private array $structure;

    public function __construct(array $structure)
    {
        $this->structure = $structure;
    }

    /** @return MetricInterface[] */
    public function parseMetrics(array $data): array
    {
        return $this->walkArrayLeaf($data, $this->structure);
    }

    /** @return MetricInterface[] */
    protected function walkArrayLeaf(array $data, array $structure): array
    {
        $metrics = [];

        foreach ($structure as $structureKey => $structureValue) {
            if (!array_key_exists($structureKey, $data)) {
                continue;
            }

            if ($structureValue instanceof ParserControlStructureInterface) {
                $structureValue = $structureValue->parse($data[$structureKey]);
            }

            if (is_array($structureValue)) {
                if (!is_array($data[$structureKey]) ) {
                    continue;
                }

                $metrics[] = $this->walkArrayLeaf($data[$structureKey], $structureValue);
                continue;
            }

            if ($structureValue instanceof ParserNodeInterface) {
                $metrics[] = [$structureValue->parse($data[$structureKey])];
            }
        }

        return array_merge(...$metrics);
    }
}