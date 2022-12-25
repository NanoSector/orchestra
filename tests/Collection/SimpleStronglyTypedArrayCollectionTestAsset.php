<?php

declare(strict_types=1);

namespace App\Tests\Collection;

use Infrastructure\Collection\AbstractStronglyTypedArrayCollection;
use stdClass;

class SimpleStronglyTypedArrayCollectionTestAsset extends AbstractStronglyTypedArrayCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(stdClass::class, $elements);
    }
}