<?php

namespace App\Enums;

enum TaskSequence: int
{
    case ONE_BY_ONE = 1;
    case MULTIPLE_AT_ONCE = 2;
}
