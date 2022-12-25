<?php
declare(strict_types=1);

namespace Infrastructure\Doctrine\Traits;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

trait TimestampedEntityTrait
{
    #[ORM\Column(type: 'CarbonDateTimeType')]
    protected Carbon $createdAt;

    #[ORM\Column(type: 'CarbonDateTimeType', nullable: true)]
    protected ?Carbon $updatedAt = null;

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = Carbon::now();
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = Carbon::now();
    }

}