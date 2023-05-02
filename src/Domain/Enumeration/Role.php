<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Enumeration;

enum Role: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_USER = 'ROLE_USER';

    case SYMFONY_ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    /** @return Role[] */
    public static function all(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
        ];
    }

    public function equals(mixed $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $other->value === $this->value;
    }
}
