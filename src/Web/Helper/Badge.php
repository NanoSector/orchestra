<?php
declare(strict_types=1);

namespace Web\Helper;

enum Badge: string
{
    case OK = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case DANGER = 'danger';
}