<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Bundle\Endpoint\Domain;

/**
 * RFC3875-compliant server software parser
 *
 * SERVER_SOFTWARE is defined in section 4.1.17 of the document
 * and is what this class targets.
 *
 * @see https://www.rfc-editor.org/rfc/rfc3875
 */
class ServerSoftwareParser
{
    public static function parse(string $value): ?SoftwareDetails
    {
        if (empty($value)) {
            return null;
        }

        $parts = explode('/', $value);

        if (count($parts) >= 2) {
            return new SoftwareDetails(
                name         : $parts[0],
                versionString: $parts[1]
            );
        }

        // QUIRK: Symfony local development server does not conform to RFC3875
        // https://github.com/symfony-cli/symfony-cli/commit/19fb53026822fc837a81581ce938a368eeda5b01
        if (preg_match('/^Symfony Local Server (.+)$/', $value, $matches)) {
            return new SoftwareDetails(
                name         : 'Symfony-Local-Server',
                versionString: $matches[1]
            );
        }

        return null;
    }
}
