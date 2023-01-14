<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Exception;

class ParserNodeTypeException extends RuntimeException
{
    public static function mismatch(string $got, string $wanted): self
    {
        return new self(sprintf('Got %s, wanted %s; check your parser definition', $got, $wanted));
    }
}