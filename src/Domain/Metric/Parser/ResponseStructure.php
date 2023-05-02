<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Metric\Parser;

use Orchestra\Domain\Metric\MetricInterface;
use Orchestra\Domain\Metric\Parser\Node\ParserControlStructureInterface;
use Orchestra\Domain\Metric\Parser\Node\ParserNodeInterface;

readonly class ResponseStructure
{
    public function __construct(
        protected array $structure
    ) {
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
                if (!is_array($data[$structureKey])) {
                    continue;
                }

                $metrics[] = $this->walkArrayLeaf($data[$structureKey], $structureValue);
                continue;
            }

            if ($structureValue instanceof ParserNodeInterface) {
                $value = $data[$structureKey];

                if (!is_array($value)) {
                    $value = (string)$value;
                }

                $metrics[] = [$structureValue->parse($value)];
            }
        }

        return array_merge(...$metrics);
    }
}
