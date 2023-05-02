<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Orchestra\Domain\Enumeration\Role;
use Orchestra\Infrastructure\Collection\AbstractStronglyTypedArrayCollection;

/**
 * @extends AbstractStronglyTypedArrayCollection<array-key, Role>
 */
class RoleCollection extends AbstractStronglyTypedArrayCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(Role::class, $elements);
    }

    public function asStringCollection(): ArrayCollection
    {
        return $this->map(static fn(Role $r) => $r->value);
    }

    public function unique(): RoleCollection
    {
        $result = new RoleCollection();
        foreach ($this as $item) {
            if ($result->contains($item)) {
                continue;
            }

            $result->add($item);
        }

        return $result;
    }
}
