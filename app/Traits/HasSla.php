<?php

namespace App\Traits;

use App\Models\Sla;

trait HasSla
{
    abstract function calculateSlaMinutes(): int;

    function getSlaAttribute(): Sla
    {
        return $this->slas->last();
    }
}
