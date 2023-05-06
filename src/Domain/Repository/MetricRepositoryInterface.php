<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Repository;

use Orchestra\Domain\Entity\Metric;

interface MetricRepositoryInterface
{
    /**
     * Deletes the entity from the repository, if it exists.
     */
    public function delete(Metric $entity): void;

    /**
     * Saves the entity to the repository, either creating or updating it as necessary.
     */
    public function save(Metric $entity): void;
}
