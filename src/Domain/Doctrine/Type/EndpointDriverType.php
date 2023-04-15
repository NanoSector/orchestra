<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Doctrine\Type;

use Orchestra\Domain\Endpoint\Driver\DriverEnum;
use Orchestra\Infrastructure\Doctrine\Type\AbstractEnumType;

class EndpointDriverType extends AbstractEnumType
{
    public static function getEnumsClass(): string
    {
        return DriverEnum::class;
    }

    public function getName(): string
    {
        return __CLASS__;
    }
}