<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Orchestra\Infrastructure\Doctrine\Traits;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Orchestra\Domain\Doctrine\Interface\SoftDeleteInterface;

trait SoftDeleteEntityTrait
{
    #[ORM\Column]
    protected bool $deleted = false;

    #[ORM\Column(type: 'CarbonDateTimeType', nullable: true)]
    protected ?Carbon $deletedAt = null;

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function getDeletedAt(): ?Carbon
    {
        return $this->deletedAt;
    }

    #[ORM\PreRemove]
    public function softDelete(): void
    {
        $this->deleted = true;
        $this->deletedAt = Carbon::now();
    }

}