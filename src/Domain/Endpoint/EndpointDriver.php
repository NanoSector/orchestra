<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Domain\Endpoint;

use Domain\Endpoint\Driver\NextcloudOcs\NextcloudOcsDriver;

enum EndpointDriver: string
{
    case NextcloudOcsDriver = NextcloudOcsDriver::class;

    public function getFriendlyName(): string
    {
        return match ($this) {
            self::NextcloudOcsDriver => 'Nextcloud (OCS)',
        };
    }
}
