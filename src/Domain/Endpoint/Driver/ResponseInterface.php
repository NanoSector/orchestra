<?php

declare(strict_types=1);

namespace Domain\Endpoint\Driver;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Metric\MetricInterface;

interface ResponseInterface
{
    /**
     * @return ArrayCollection<MetricInterface>
     */
    public function getMetrics(): ArrayCollection;
}