<?php

declare(strict_types = 1);

namespace Api\Support;

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