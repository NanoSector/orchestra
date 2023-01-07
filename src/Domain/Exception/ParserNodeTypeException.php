<?php

declare(strict_types = 1);

namespace Domain\Exception;

class ParserNodeTypeException extends RuntimeException
{
    public static function mismatch(string $got, string $wanted): self
    {
        return new self(sprintf('Got %s, wanted %s; check your parser definition', $got, $wanted));
    }
}