<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Web\Breadcrumb;

use Orchestra\Web\Exception\BreadcrumbException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @extends ParameterBag<BreadcrumbItem>
 */
class BreadcrumbBag extends ParameterBag
{
    /**
     * @throws BreadcrumbException
     */
    public function replace(array $breadcrumbs = [])
    {
        $this->parameters = [];
        $this->add($breadcrumbs);
    }

    /**
     * @throws BreadcrumbException
     */
    public function add(array $breadcrumbs = [])
    {
        try {
            Assert::allString(array_keys($breadcrumbs));
        } catch (InvalidArgumentException $exception) {
            throw new BreadcrumbException('Breadcrumbs must have string keys', 0, $exception);
        }

        foreach ($breadcrumbs as $key => $breadcrumb) {
            $this->set($key, $breadcrumb);
        }
    }

    /**
     * @throws BreadcrumbException
     */
    public function set(string $key, mixed $value)
    {
        if (!$value instanceof BreadcrumbItem) {
            throw new BreadcrumbException('A breadcrumb must be an instance of BreadcrumbItem');
        }

        parent::set($key, $value);
    }
}
