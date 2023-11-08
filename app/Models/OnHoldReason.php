<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnHoldReason extends MappableModel
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

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getNameAttribute($value): string
    {
        $value = str_replace('_', ' ', $value);
        $value = ucwords($value);

        return $value;
    }
}
