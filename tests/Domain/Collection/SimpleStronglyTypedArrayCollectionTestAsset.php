<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Tests\Collection;

use Orchestra\Infrastructure\Collection\AbstractStronglyTypedArrayCollection;
use stdClass;

class SimpleStronglyTypedArrayCollectionTestAsset extends AbstractStronglyTypedArrayCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(stdClass::class, $elements);
    }
}