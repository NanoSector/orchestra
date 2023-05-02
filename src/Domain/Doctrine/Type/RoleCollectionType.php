<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Orchestra\Domain\Collection\RoleCollection;
use Orchestra\Domain\Enumeration\Role;

class RoleCollectionType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (!$value instanceof RoleCollection) {
            return null;
        }

        return implode(
            ',',
            array_map(static fn(Role $e) => $e->value, $value->getValues())
        );
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): RoleCollection
    {
        if ($value === null) {
            return new RoleCollection();
        }

        $value = is_resource($value) ? stream_get_contents($value) : $value;

        return new RoleCollection(
            array_map(
                static fn(string $e) => Role::from($e),
                explode(',', (string)$value)
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::class;
    }

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }
}
