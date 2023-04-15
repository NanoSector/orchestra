<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Domain\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Orchestra\Domain\Entity\Metric;
use Orchestra\Domain\Exception\InvalidArgumentException;

class MetricsPerProductCollection
{
    /**
     * @var Collection<string, ArrayCollection<Metric>>
     */
    private Collection $state;

    public function __construct()
    {
        $this->state = new ArrayCollection();
    }

    /**
     * @param Collection<Metric> $collection
     */
    public static function fromMetricCollection(Collection $collection): Collection
    {
        $self = new self();

        $collection->forAll(fn(int $key, Metric $m) => $self->add($m));

        return $self->getResult();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function add(Metric $metric): self
    {
        if ($metric->getProduct() === null) {
            throw new InvalidArgumentException(sprintf("Product cannot be null when using %s", __CLASS__));
        }

        if (!$this->state->containsKey($metric->getProduct())) {
            $this->state->set($metric->getProduct(), new ArrayCollection());
        }

        $this->state->get($metric->getProduct())->add($metric);

        return $this;
    }

    /**
     * @return Collection<string, Metric>
     */
    public function getResult(): Collection
    {
        return $this->state;
    }

}