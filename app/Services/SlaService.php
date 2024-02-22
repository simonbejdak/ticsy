<?php

namespace App\Services;

use App\Interfaces\SLAble;
use App\Models\Sla;
use Illuminate\Support\Carbon;

class SlaService
{
    public static function createSla(SLAble $slable): void
    {
        $sla = new Sla;
        $sla->slable()->associate($slable);
        $sla->expires_at = Carbon::now()->addMinutes($slable->calculateSlaMinutes());
        $sla->save();
    }

    public static function closeSla(Sla|null $sla): void
    {
        if($sla){
            $sla->closed_at = Carbon::now();
            $sla->save();
        }
    }
}
