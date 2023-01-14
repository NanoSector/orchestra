<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Infrastructure\Breadcrumbs;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @extends ParameterBag<BreadcrumbItem>
 */
class BreadcrumbBag extends ParameterBag
{
    public function replace(array $breadcrumbs = [])
    {
        $this->parameters = [];
        $this->add($breadcrumbs);
    }

    public function set(string $key, mixed $value)
    {
        if (!$value instanceof BreadcrumbItem) {
            throw new \InvalidArgumentException('A breadcrumb must be an instance of BreadcrumbItem');
        }

        parent::set($key, $value);
    }

    public function add(array $breadcrumbs = [])
    {
        foreach ($breadcrumbs as $key => $breadcrumb) {
            $this->set($key, $breadcrumb);
        }
    }
}
