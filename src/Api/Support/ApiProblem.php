<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Api\Support;

use JsonSerializable;
use Orchestra\Api\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Implementation of RFC7807
 */
class ApiProblem implements JsonSerializable
{
    protected string $type = 'orchestra://problem/generic';

    public function __construct(
        protected string $title,
        protected int $status,
        protected ?string $detail = null
    ) {
    }

    public static function fromHttpCode(int $code, ?string $detail = null): self
    {
        $statusText = Response::$statusTexts[$code] ?? 'Unknown';

        $self = new ApiProblem($statusText, $code, $detail);
        $self->setType('orchestra://problem/http/' . $code);

        return $self;
    }

    public function setType(string $type): static
    {
        if (!filter_var($type, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('RFC7807 mandates that the API Problem type is a valid URI');
        }

        $this->type = $type;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'type'   => $this->type,
            'title'  => $this->title,
            'status' => $this->status,
            'detail' => $this->detail,
        ];
    }
}
