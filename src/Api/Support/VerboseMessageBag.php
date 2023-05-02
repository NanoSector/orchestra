<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Api\Support;

final class VerboseMessageBag
{
    /**
     * @var string[]
     */
    private array $messages = [];

    public function add(string $message): self
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAll(): array
    {
        return $this->messages;
    }
}
