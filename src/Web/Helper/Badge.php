<?php
/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

declare(strict_types=1);

namespace Web\Helper;

enum Badge: string
{
    case OK = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case DANGER = 'danger';
}