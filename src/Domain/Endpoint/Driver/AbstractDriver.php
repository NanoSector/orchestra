<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Endpoint\Driver;

abstract class AbstractDriver implements DriverInterface
{
    public function sanitizeConfiguration(array $options): array
    {
        $skeleton = $this->getConfigurationSkeleton();

        return $this->sanitizeConfigurationLeaf($options, $skeleton);
    }

    protected function sanitizeConfigurationLeaf(array $options, array $skeleton)
    {
        $options = array_intersect_key($options, $skeleton);

        foreach ($skeleton as $skeletonKey => $skeletonValue) {
            $shouldBeArray = is_array($skeletonValue);

            if (!array_key_exists($skeletonKey, $options)) {
                $options[$skeletonKey] = $shouldBeArray ? [] : null;
            }

            if ($shouldBeArray) {
                if (!is_array($options[$skeletonKey]) ) {
                    $options[$skeletonKey] = [];
                }

                $options[$skeletonKey] = $this->sanitizeConfigurationLeaf($options[$skeletonKey], $skeletonValue);
            }
        }

        return $options;
    }
}