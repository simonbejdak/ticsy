<?php

namespace App\Models;

use App\Models\Incident;
use App\Models\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnHoldReason extends Enum
{
    use HasFactory;

    const MAP = [
        'caller_response' => 1,
        'monitoring' => 2,
        'waiting_for_vendor' => 3,
        'waiting_for_change' => 4,
    ];

    const CALLER_RESPONSE = self::MAP['caller_response'];
    const MONITORING = self::MAP['monitoring'];
    const WAITING_FOR_VENDOR = self::MAP['waiting_for_vendor'];
    const WAITING_FOR_CHANGE = self::MAP['waiting_for_change'];

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
