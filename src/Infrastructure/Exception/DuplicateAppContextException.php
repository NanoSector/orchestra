<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Infrastructure\Exception;

use Exception;

class DuplicateAppContextException extends Exception
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            'The current request already defines an AppContext; did you set multiple AppContext attributes?',
            $code,
            $previous
        );
    }
}