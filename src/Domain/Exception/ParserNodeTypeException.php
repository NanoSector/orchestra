<?php

namespace Domain\Exception;

use Exception;

class ParserNodeTypeException extends Exception
{
    public static function mismatch(string $got, string $wanted): self
    {
        return new self(sprintf('Got %s, wanted %s; check your parser definition', $got, $wanted));
    }
}