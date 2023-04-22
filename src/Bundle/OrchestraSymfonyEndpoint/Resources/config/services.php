<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types = 1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Orchestra\Bundle\Endpoint\Controller\OrchestraDetailsController;

return static function (ContainerConfigurator $container) {
    $container->services()
              ->set('orchestra.controller.details', OrchestraDetailsController::class)
              ->public()
              ->args([
                  service('data_collector.config')->nullOnInvalid()
              ]);
};
