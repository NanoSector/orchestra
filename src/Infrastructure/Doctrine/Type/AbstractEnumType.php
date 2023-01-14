<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Infrastructure\Doctrine\Type;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LogicException;

/**
 * Based on code from https://debest.fr/en/blog/php-8-1-enums-doctrine-and-symfony-enumtype
 */
abstract class AbstractEnumType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TEXT';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }
        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (false === enum_exists($this::getEnumsClass())) {
            throw new LogicException("This class should be an enum");
        }

        return $this::getEnumsClass()::tryFrom($value);
    }

    /**
     * @return class-string<BackedEnum> an enum class string
     */
    abstract public static function getEnumsClass(): string;
}