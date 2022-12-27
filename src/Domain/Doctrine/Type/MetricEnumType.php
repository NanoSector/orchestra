<?php

declare(strict_types=1);

namespace Domain\Doctrine\Type;

use Domain\Endpoint\EndpointDriver;
use Domain\Metric\MetricEnum;
use Infrastructure\Doctrine\Type\AbstractEnumType;

class MetricEnumType extends AbstractEnumType
{
    public static function getEnumsClass(): string
    {
        return MetricEnum::class;
    }

    public function getName(): string
    {
        return __CLASS__;
    }
}