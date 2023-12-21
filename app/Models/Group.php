<?php

namespace App\Models;

use App\Models\Incident;
use App\Models\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Enum
{
    use HasFactory;

    const MAP = [
        'SERVICE-DESK' => 1,
        'LOCAL-6445-NEW-YORK' => 2,
    ];
    const SERVICE_DESK = self::MAP['SERVICE-DESK'];
    const LOCAL_6445_NEW_YORK = self::MAP['LOCAL-6445-NEW-YORK'];

    public function resolvers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
