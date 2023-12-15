<?php

namespace App\Models\Incident;

use App\Models\Enum;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncidentStatus extends Enum
{
    use HasFactory;

    const MAP = [
        'open' => 1,
        'in_progress' => 2,
        'on_hold' => 3,
        'monitoring' => 4,
        'resolved' => 5,
        'cancelled' => 6,
    ];

    const OPEN = self::MAP['open'];
    const IN_PROGRESS = self::MAP['in_progress'];
    const ON_HOLD = self::MAP['on_hold'];
    const MONITORING = self::MAP['monitoring'];
    const RESOLVED = self::MAP['resolved'];
    const CANCELLED = self::MAP['cancelled'];

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'status_id');
    }
}
