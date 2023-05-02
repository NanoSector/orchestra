<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint\GenericResponse;

use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use Orchestra\Domain\Endpoint\Driver\AbstractDriverResponse;
use Orchestra\Domain\Metric\VersionMetric;

readonly class PlaintextVersionResponse extends AbstractDriverResponse
{
    /**
     * @throws InvalidArgumentException when version string does not look like a version string
     */
    public function __construct(string $product, string $version)
    {
        if (strlen($version) > 20) {
            throw new InvalidArgumentException('Version string is very long; is this really a version string?');
        }

        // Strip any leading v's (e.g. v1.2.0)
        $version = ltrim($version, "v");

        parent::__construct(
            new ArrayCollection([
                new VersionMetric($product, $version),
            ])
        );
    }
}
