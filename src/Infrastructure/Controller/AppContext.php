<?php
declare(strict_types=1);

namespace Infrastructure\Controller;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AppContext
{
    public readonly string $appContext;

    public function __construct(string $appContext)
    {
        $this->appContext = $appContext;
    }
}