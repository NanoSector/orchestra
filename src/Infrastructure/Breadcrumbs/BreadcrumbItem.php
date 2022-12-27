<?php
declare(strict_types=1);

namespace Infrastructure\Breadcrumbs;

class BreadcrumbItem
{
    public readonly string $name;

    public readonly ?string $url;

    public readonly bool $active;

    public function __construct(string $name, ?string $url, bool $active = false)
    {
        $this->name = $name;
        $this->active = $active;

        if ($active) {
            $url = null;
        }

        $this->url = $url;
    }
}