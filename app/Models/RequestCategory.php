<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestCategory extends MappableModel
{
    use HasFactory;

    const MAP = [
        'network' => 1,
        'server' => 2,
        'computer' => 3,
        'application' => 4,
        'email' => 5,
    ];

    const NETWORK = self::MAP['network'];
    const SERVER = self::MAP['server'];
    const COMPUTER = self::MAP['computer'];
    const APPLICATION = self::MAP['application'];
    const EMAIL = self::MAP['email'];

    function requests(): hasMany{
        return $this->hasMany(Request::class, 'category_id');
    }

    function getNameAttribute($value): string
    {
        return ucfirst($value);
    }
}
