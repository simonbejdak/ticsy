<?php

namespace App\Traits;

use App\Models\Sla;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait SLAs
{
    abstract function calculateSlaMinutes(): int;

    function slas(): MorphMany
    {
        return $this->morphMany(Sla::class, 'slable');
    }

    function getSlaAttribute(): Sla|null
    {
        return $this->slas()->opened()->latest()->first();
    }
}
