<?php

namespace App\Enums;

enum Status: int
{
    case OPEN = 1;
    case IN_PROGRESS = 2;
    case ON_HOLD = 3;
    case MONITORING = 4;
    case RESOLVED = 5;
    case CANCELLED = 6;
}
