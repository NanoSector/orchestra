<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Domain\Collection\RoleCollection;
use Domain\Enumeration\Role;

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
                explode(',', $value)
            )
        );
    }


    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return __CLASS__;
    }
}