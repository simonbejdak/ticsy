<?php

namespace App\Services;

use App\Helpers\Slable;
use App\Models\Sla;
use Illuminate\Support\Carbon;

class SlaService
{
    public static function createSla(Slable $slable): void
    {
        $sla = new Sla;
        $sla->slable()->associate($slable);
        $sla->expires_at = Carbon::now()->addMinutes($slable->calculateSlaMinutes());
        $sla->save();
    }

    public static function closeSla(Sla $sla): void
    {
        $sla->closed_at = Carbon::now();
        $sla->save();
    }
}
