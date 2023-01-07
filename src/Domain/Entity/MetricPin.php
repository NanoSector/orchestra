<?php
declare(strict_types=1);

namespace Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class MetricPin
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Metric::class)]
    private Metric $metric;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pinnedMetrics')]
    private User $user;

    public function __construct(Metric $metric, User $user)
    {
        $this->metric = $metric;
        $this->user = $user;
    }


    public function getMetric(): Metric
    {
        return $this->metric;
    }

    public function getUser(): User
    {
        return $this->user;
    }


}