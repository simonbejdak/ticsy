<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends MappableModel
{
    use HasFactory;

    const MAP = [
        'SERVICE-DESK' => 1,
        'LOCAL-6445-NEW-YORK' => 2,
    ];
    const SERVICE_DESK = self::MAP['SERVICE-DESK'];
    const LOCAL_6445_NEW_YORK = self::MAP['LOCAL-6445-NEW-YORK'];
    const DEFAULT = self::SERVICE_DESK;

    public function resolvers()
    {
        return $this->belongsToMany(User::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
