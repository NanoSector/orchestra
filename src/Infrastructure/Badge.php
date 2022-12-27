<?php
declare(strict_types=1);

namespace Infrastructure;

enum Badge: string
{
    case OK = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case DANGER = 'danger';
}