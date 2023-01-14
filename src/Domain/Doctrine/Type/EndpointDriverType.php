<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Doctrine\Type;

use Domain\Endpoint\EndpointDriver;
use Infrastructure\Doctrine\Type\AbstractEnumType;

class EndpointDriverType extends AbstractEnumType
{
    public static function getEnumsClass(): string
    {
        return EndpointDriver::class;
    }

    public function getName(): string
    {
        return __CLASS__;
    }
}