<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Api\Support;

use Orchestra\Api\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

final class LinkBag
{
    /** @var array<string, string> */
    private array $links = [];

    public function addSelf(Request $request): self
    {
        $this->set('self', $request->getUri());

        return $this;
    }

    public function set(string $key, string $url): self
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('API links set must be valid URLs');
        }

        $this->links[$key] = $url;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getAll(): array
    {
        return $this->links;
    }
}
