<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Doctrine\Type;

use Orchestra\Domain\Metric\MetricEnum;
use Orchestra\Infrastructure\Doctrine\Type\AbstractEnumType;

class MetricEnumType extends AbstractEnumType
{
    public static function getEnumsClass(): string
    {
        return MetricEnum::class;
    }

    public function getName(): string
    {
        return self::class;
    }
}
