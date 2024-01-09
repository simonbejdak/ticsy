<?php

namespace App\Enums;

use Illuminate\Contracts\Support\Arrayable;

enum Status: string
{
    case OPEN = 'Open';
    case IN_PROGRESS = 'In Progress';
    case ON_HOLD = 'On Hold';
    case MONITORING = 'Monitoring';
    case RESOLVED = 'Resolved';
    case CANCELLED = 'Cancelled';
}
