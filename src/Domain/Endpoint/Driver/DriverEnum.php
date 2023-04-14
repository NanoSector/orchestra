<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Endpoint\Driver;

use Domain\Endpoint\Driver\NextcloudOcs\NextcloudOcsDriver;

enum DriverEnum: string
{
    case NextcloudOcsDriver = NextcloudOcsDriver::class;

    public function getContainerDefinition(): string
    {
        return $this->value;
    }

    public function getFriendlyName(): string
    {
        return match ($this) {
            self::NextcloudOcsDriver => 'Nextcloud (OCS)',
        };
    }
}
