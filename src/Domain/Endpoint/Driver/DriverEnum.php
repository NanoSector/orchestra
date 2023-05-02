<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Endpoint\Driver;

use Orchestra\Domain\Endpoint\Driver\Bazarr\BazarrDriver;
use Orchestra\Domain\Endpoint\Driver\GenericPlaintextVersion\GenericPlaintextVersionDriver;
use Orchestra\Domain\Endpoint\Driver\NextcloudOcs\NextcloudOcsDriver;
use Orchestra\Domain\Endpoint\Driver\OrchestraEndpoint\OrchestraEndpointDriver;
use Orchestra\Domain\Endpoint\Driver\PlexMediaServer\PlexMediaServerDriver;
use Orchestra\Domain\Endpoint\Driver\Sonarr\SonarrDriver;

enum DriverEnum: string
{
    case BazarrDriver = BazarrDriver::class;
    case GenericPlaintextVersionDriver = GenericPlaintextVersionDriver::class;
    case NextcloudOcsDriver = NextcloudOcsDriver::class;
    case OrchestraEndpointDriver = OrchestraEndpointDriver::class;
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
            self::OrchestraEndpointDriver => 'Orchestra Endpoint',
            self::PlexMediaServerDriver => 'Plex Media Server',
            self::Sonarr => 'Sonarr & co (Radarr, Prowlarr, etc.)'
        };
    }
}
