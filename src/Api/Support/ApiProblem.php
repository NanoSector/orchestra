<?php

declare(strict_types = 1);

namespace Api\Support;

use Api\Exception\InvalidArgumentException;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Implementation of RFC7807
 */
class ApiProblem implements JsonSerializable
{
    protected string $type = 'orchestra://problem/generic';
    protected string $title;
    protected int $status;
    protected ?string $detail;

    public function __construct(string $title, int $status, ?string $detail = null)
    {
        $this->title = $title;
        $this->status = $status;
        $this->detail = $detail;
    }

    public static function fromHttpCode(int $code, ?string $detail = null): self
    {
        $statusText = Response::$statusTexts[$code] ?? 'Unknown';

        return new ApiProblem($statusText, $code, $detail);
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

    public function setType(string $type): static
    {
        if (!filter_var($type, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('RFC7807 mandates that the API Problem type is a valid URI');
        }

        $this->type = $type;

        return $this;
    }
}