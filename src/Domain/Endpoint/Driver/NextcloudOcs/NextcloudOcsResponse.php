<?php
declare(strict_types=1);

namespace Domain\Endpoint\Driver\NextcloudOcs;

use Domain\Endpoint\Driver\AbstractResponse;
use Domain\Endpoint\Driver\ResponseWithHealthCheckInterface;
use Domain\Endpoint\Driver\ResponseWithHealthCheckTrait;

class NextcloudOcsResponse extends AbstractResponse implements ResponseWithHealthCheckInterface
{
    use ResponseWithHealthCheckTrait;
}