<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Doctrine\Type;

use Domain\Metric\MetricEnum;
use Infrastructure\Doctrine\Type\AbstractEnumType;

class MetricEnumType extends AbstractEnumType
{
    public static function getEnumsClass(): string
    {
        return MetricEnum::class;
    }

    public function getName(): string
    {
        return __CLASS__;
    }
}