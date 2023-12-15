<?php

namespace App\Models\Request;

use App\Models\Enum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestStatus extends Enum
{
    use HasFactory;

    const MAP = [
        'open' => 1,
        'in_progress' => 2,
        'on_hold' => 3,
        'monitoring' => 4,
        'closed' => 5,
        'cancelled' => 6,
    ];

    const OPEN = self::MAP['open'];
    const IN_PROGRESS = self::MAP['in_progress'];
    const ON_HOLD = self::MAP['on_hold'];
    const MONITORING = self::MAP['monitoring'];
    const CLOSED = self::MAP['closed'];
    const CANCELLED = self::MAP['cancelled'];

    function requests(): hasMany{
        return $this->hasMany(Request::class, 'status_id');
    }
}
