<?php

namespace App\Models\Request;

use App\Models\Enum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestOnHoldReason extends Enum
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

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class, 'on_hold_reason_id');
    }
}
