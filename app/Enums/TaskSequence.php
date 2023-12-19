<?php

namespace App\Enums;

enum TaskSequence: int
{
    case GRADUAL = 1;
    case AT_ONCE = 2;
}
