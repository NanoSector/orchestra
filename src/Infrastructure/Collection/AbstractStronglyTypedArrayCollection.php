<?php

declare(strict_types=1);

namespace Infrastructure\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

/**
 * @psalm-template TKey of array-key
 * @psalm-template T
 * @extends ArrayCollection<TKey, T>
 */
abstract class AbstractStronglyTypedArrayCollection extends ArrayCollection
{
    /** @var class-string<T> */
    protected string $type;

    /** @param class-string<T> $type */
    public function __construct(string $type, array $elements = [])
    {
        $this->type = $type;

        Assert::allIsInstanceOf($elements, $type);
        parent::__construct($elements);
    }

    /**
     * @param string|int $key
     * @param T $value
     * @return void
     */
    public function set(int|string $key, mixed $value): void
    {
        Assert::isInstanceOf($value, $this->type);
        parent::set($key, $value);
    }

    /**
     * @param T $element
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
    public function map(\Closure $func): ArrayCollection
    {
        // Force a new ArrayCollection because we cannot be sure what type we mapped to.
        return new ArrayCollection(array_map($func, $this->toArray()));
    }


}