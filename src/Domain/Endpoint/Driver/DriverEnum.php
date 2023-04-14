<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Endpoint\Driver;

use Domain\Endpoint\Driver\Bazarr\BazarrDriver;
use Domain\Endpoint\Driver\GenericPlaintextVersion\GenericPlaintextVersionDriver;
use Domain\Endpoint\Driver\NextcloudOcs\NextcloudOcsDriver;
use Domain\Endpoint\Driver\PlexMediaServer\PlexMediaServerDriver;
use Domain\Endpoint\Driver\Sonarr\SonarrDriver;

enum DriverEnum: string
{
    case BazarrDriver = BazarrDriver::class;
    case GenericPlaintextVersionDriver = GenericPlaintextVersionDriver::class;
    case NextcloudOcsDriver = NextcloudOcsDriver::class;
    case PlexMediaServerDriver = PlexMediaServerDriver::class;
    case Sonarr = SonarrDriver::class;

    public function getContainerDefinition(): string
    {
        return $this->value;
    }

    public function getFriendlyName(): string
    {
        return match ($this) {
            self::BazarrDriver => 'Bazarr',
            self::GenericPlaintextVersionDriver => 'Plain text version (generic)',
            self::NextcloudOcsDriver => 'Nextcloud (OCS)',
            self::PlexMediaServerDriver => 'Plex Media Server',
            self::Sonarr => 'Sonarr & co (Radarr, Prowlarr, etc.)'
        };
    }
}
