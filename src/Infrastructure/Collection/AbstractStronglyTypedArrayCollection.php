<?php

/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Infrastructure\Collection;

use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

/**
 * @psalm-template TKey of array-key
 * @psalm-template T
 * @extends ArrayCollection<TKey, T>
 */
abstract class AbstractStronglyTypedArrayCollection extends ArrayCollection
{
    /** @param class-string<T> $type */
    public function __construct(
        protected string $type,
        array $elements = []
    ) {
        Assert::allIsInstanceOf($elements, $type);
        parent::__construct($elements);
    }

    /**
     * @param T $element
     *
     * @return void
     */
    public function add(mixed $element): void
    {
        Assert::isInstanceOf($element, $this->type);
        parent::add($element);
    }

    /**
     * {@inheritDoc}
     */
    public function map(Closure $func): ArrayCollection
    {
        // Force a new ArrayCollection because we cannot be sure what type we mapped to.
        return new ArrayCollection(array_map($func, $this->toArray()));
    }

    /**
     * @param string|int $key
     * @param T          $value
     *
     * @return void
     */
    public function set(int|string $key, mixed $value): void
    {
        Assert::isInstanceOf($value, $this->type);
        parent::set($key, $value);
    }
}
