<?php

namespace Infrastructure\Doctrine\Traits;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Domain\Doctrine\Interface\SoftDeleteInterface;

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