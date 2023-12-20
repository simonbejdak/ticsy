<?php

namespace App\Traits;

use App\Models\Sla;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSla
{
    abstract function calculateSlaMinutes(): int;

    function slas(): MorphMany
    {
        return $this->morphMany(Sla::class, 'slable');
    }

    function getSlaAttribute(): Sla
    {
        return $this->slas->last();
    }
}
