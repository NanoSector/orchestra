<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Infrastructure\Doctrine\Exception;

use RuntimeException;
use Throwable;

class EntityHardDeletedException extends RuntimeException
{
    public function __construct($entity, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf("Doctrine is about to hard-delete a soft-deletable entity of type %s", $entity::class),
            $code,
            $previous
        );
    }
}
