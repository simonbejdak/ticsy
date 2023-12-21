<?php

namespace App\Models;

use App\Models\Incident;
use App\Models\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Enum
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

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
