<?php

namespace App\Models;

use Countable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends MappableModel
{
    use HasFactory;

    const MAP = [
        'incident' => 1,
        'request' => 2,
        'change' => 3,
    ];
    const DEFAULT = self::INCIDENT;
    const INCIDENT = self::MAP['incident'];
    const REQUEST = self::MAP['request'];
    const CHANGE = self::MAP['change'];

    protected $guarded = [];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
