<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Domain\Endpoint\Driver\NextcloudOcs;

use Domain\Endpoint\Driver\AbstractDriverResponse;
use Domain\Endpoint\Driver\DriverResponseWithHealthCheckInterface;
use Domain\Endpoint\Driver\DriverResponseWithHealthCheckTrait;

class NextcloudOcsDriverResponse extends AbstractDriverResponse implements DriverResponseWithHealthCheckInterface
{
    use DriverResponseWithHealthCheckTrait;
}