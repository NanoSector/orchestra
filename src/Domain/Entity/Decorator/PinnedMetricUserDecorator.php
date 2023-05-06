<?php

/**
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Orchestra\Domain\Entity\Decorator;

use Doctrine\Common\Collections\ReadableCollection;
use Orchestra\Domain\Entity\Application;
use Orchestra\Domain\Entity\Endpoint;
use Orchestra\Domain\Entity\Metric;
use Orchestra\Domain\Entity\MetricPin;
use Orchestra\Domain\Entity\User;

readonly class PinnedMetricUserDecorator
{
    public function __construct(
        private User $user
    ) {
    }

    /**
     * @param Application $application
     * @return ReadableCollection<MetricPin>
     */
    public function findPinsByApplication(Application $application): ReadableCollection
    {
        return $this->user->getPinnedMetrics()->filter(
            function (MetricPin $p) use ($application) {
                $endpoint = $p->getMetric()->getEndpoint();

                if (!$endpoint instanceof Endpoint) {
                    return false;
                }

                return $endpoint->getApplication()->getId() === $application->getId();
            }
        );
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function pinMetric(Metric $metric): self
    {
        if (!$this->hasPinnedMetric($metric)) {
            $pin = new MetricPin($metric, $this->user);
            $this->user->getPinnedMetrics()->add($pin);
        }

        return $this;
    }

    public function hasPinnedMetric(Metric $metric): bool
    {
        return $this->findPinByMetric($metric) instanceof MetricPin;
    }

    public function findPinByMetric(Metric $metric): ?MetricPin
    {
        return $this->user->getPinnedMetrics()->findFirst(
            fn(int $key, MetricPin $p) => $p->getMetric() === $metric
        );
    }

    public function unpinMetric(Metric $metric): self
    {
        $pin = $this->findPinByMetric($metric);

        if ($pin instanceof MetricPin) {
            $this->user->getPinnedMetrics()->removeElement($pin);
        }

        return $this;
    }
}
