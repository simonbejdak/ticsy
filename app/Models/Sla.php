<?php

namespace App\Models;

use App\Helpers\Slable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

class Sla extends Model
{
    protected $casts = [
        'expires_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function slable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function newSla(Slable $slable, int $minutes): self{
        $sla = new self;
        $sla->slable()->associate($slable);
        $sla->expires_at = Carbon::now()->addMinutes($minutes);
        $sla->save();

        return $sla;
    }

    public function minutes()
    {
        return $this->expires_at->diffInMinutes($this->created_at);
    }

    public function toPercentage(): int
    {
        return round($this->minutesTillExpires() / $this->minutes() * 100);
    }

    public function minutesTillExpires(): int
    {
        return $this->expires_at->diffInMinutes(Carbon::now());
    }
}
