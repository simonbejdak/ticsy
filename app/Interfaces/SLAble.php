<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface SLAble
{
    function slas(): MorphMany;
    function calculateSlaMinutes(): int;
}
