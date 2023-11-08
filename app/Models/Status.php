<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends MappableModel
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

    const DEFAULT = self::OPEN;
    const OPEN = self::MAP['open'];
    const IN_PROGRESS = self::MAP['in_progress'];
    const ON_HOLD = self::MAP['on_hold'];
    const MONITORING = self::MAP['monitoring'];
    const RESOLVED = self::MAP['resolved'];
    const CANCELLED = self::MAP['cancelled'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getNameAttribute($value)
    {
        $value = str_replace('_', ' ', $value);
        $value = ucwords($value);

        return $value;
    }
}
